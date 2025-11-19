<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class LogVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $ip = $this->getClientIp($request);
            $agent = new Agent();
            $agent->setUserAgent($request->userAgent());

            // Get GeoIP information with fallback
            $geo = $this->getGeoIpData($ip);
            
            // Get host safely
            $host = $this->getHostByIp($ip);

            Visitor::create([
                'ip' => $ip,
                'host' => $host,
                'method' => $request->method(),
                'path' => $request->path(),
                'full_url' => $request->fullUrl(),
                'referer' => $request->header('referer'),
                'user_agent' => $request->userAgent(),
                'browser' => $agent->browser(),
                'browser_version' => $agent->version($agent->browser()),
                'platform' => $agent->platform(),
                'device' => $agent->device(),
                'is_mobile' => $agent->isMobile(),
                'is_tablet' => $agent->isTablet(),
                'is_desktop' => !$agent->isMobile() && !$agent->isTablet(),
                'is_bot' => $agent->isRobot(),
                'country' => $geo['country'] ?? null,
                'country_iso' => $geo['country_code'] ?? null,
                'region' => $geo['region'] ?? null,
                'city' => $geo['city'] ?? null,
                'latitude' => $geo['lat'] ?? null,
                'longitude' => $geo['lon'] ?? null,
                'timezone' => $geo['timezone'] ?? null,
                'headers' => json_encode(collect($request->headers->all())
                    ->map(fn($v) => count($v) === 1 ? $v[0] : $v)
                    ->toArray()),
                'query' => json_encode($request->query()),
                'session_id' => session()->getId(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Visitor logging failed: ' . $e->getMessage());
        }

        return $response;
    }

    /**
     * Get client IP address reliably
     */
    private function getClientIp(Request $request): string
    {
        // Check for forwarded IP first (behind proxy/load balancer)
        if ($request->header('X-Forwarded-For')) {
            $ips = explode(',', $request->header('X-Forwarded-For'));
            return trim($ips[0]);
        }

        if ($request->header('X-Real-IP')) {
            return $request->header('X-Real-IP');
        }

        return $request->ip() ?? '0.0.0.0';
    }

    /**
     * Get GeoIP data with multiple fallback methods
     */
    private function getGeoIpData(string $ip): array
    {
        if (empty($ip) || $ip === '0.0.0.0' || $ip === '127.0.0.1') {
            return [];
        }

        // Method 1: Try Torann GeoIP first
        if (class_exists(GeoIP::class)) {
            try {
                $location = GeoIP::getLocation($ip);
                if ($location && $location->default === false) {
                    return [
                        'country' => $location->country,
                        'country_code' => $location->iso_code,
                        'region' => $location->state,
                        'city' => $location->city,
                        'lat' => $location->lat,
                        'lon' => $location->lon,
                        'timezone' => $location->timezone,
                    ];
                }
            } catch (\Throwable $e) {
                Log::debug("Torann GeoIP failed for IP {$ip}: " . $e->getMessage());
            }
        }

        // Method 2: Try ipapi.co API
        try {
            $response = Http::timeout(3)->get("http://ipapi.co/{$ip}/json/");
            if ($response->successful()) {
                $data = $response->json();
                if (!isset($data['error'])) {
                    return [
                        'country' => $data['country_name'] ?? null,
                        'country_code' => $data['country_code'] ?? null,
                        'region' => $data['region'] ?? null,
                        'city' => $data['city'] ?? null,
                        'lat' => $data['latitude'] ?? null,
                        'lon' => $data['longitude'] ?? null,
                        'timezone' => $data['timezone'] ?? null,
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::debug("ipapi.co failed for IP {$ip}: " . $e->getMessage());
        }

        // Method 3: Try ip-api.com API
        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? null,
                        'country_code' => $data['countryCode'] ?? null,
                        'region' => $data['regionName'] ?? null,
                        'city' => $data['city'] ?? null,
                        'lat' => $data['lat'] ?? null,
                        'lon' => $data['lon'] ?? null,
                        'timezone' => $data['timezone'] ?? null,
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::debug("ip-api.com failed for IP {$ip}: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Get host by IP safely
     */
    private function getHostByIp(string $ip): ?string
    {
        if (empty($ip) || $ip === '0.0.0.0' || $ip === '127.0.0.1') {
            return null;
        }

        try {
            return gethostbyaddr($ip);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
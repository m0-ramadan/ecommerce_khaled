<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class WhatsAppCheck extends Command
{
    protected $signature = 'whatsapp:check {--to= : رقم لاختبار إرسال OTP}';

    protected $description = 'فحص إعدادات WhatsApp Cloud API (التوكن، الرقم، الـ WABA، القوالب، الاشتراك)';

    public function handle(): int
    {
        $token = (string) config('services.whatsapp_cloud.access_token');
        $version = (string) config('services.whatsapp_cloud.api_version', 'v25.0');
        $phoneId = (string) config('services.whatsapp_cloud.phone_number_id');
        $waba = (string) config('services.whatsapp_cloud.waba_id');
        $template = (string) config('services.whatsapp_cloud.otp_template');

        if ($token === '') {
            $this->error('WHATSAPP_CLOUD_ACCESS_TOKEN غير موجود في .env');

            return self::FAILURE;
        }

        $this->info('=== التوكن ===');
        $debug = Http::withToken($token)->get("https://graph.facebook.com/{$version}/debug_token", [
            'input_token' => $token,
        ]);

        if ($debug->successful() && ($data = $debug->json('data'))) {
            $expires = (int) ($data['expires_at'] ?? 0);
            $this->line('valid=' . json_encode($data['is_valid'] ?? null)
                . ' | app=' . ($data['app_id'] ?? '-')
                . ' | app_name=' . ($data['application'] ?? '-')
                . ' | expires=' . ($expires > 0 ? now()->setTimestamp($expires)->toDateTimeString() : 'never'));
            foreach (($data['granular_scopes'] ?? []) as $scope) {
                $this->line('  scope=' . $scope['scope'] . ' targets=' . json_encode($scope['target_ids'] ?? []));
            }
        } else {
            $this->error('التوكن غير صالح/منتهي: ' . substr($debug->body(), 0, 220));
        }

        $this->info('=== الرقم ===');
        $this->line("phone_number_id={$phoneId}");
        if ($phoneId !== '') {
            $phone = Http::withToken($token)->get("https://graph.facebook.com/{$version}/{$phoneId}", [
                'fields' => 'display_phone_number,verified_name,status,quality_rating,platform_type',
            ]);
            $this->line($phone->successful() ? json_encode($phone->json(), JSON_UNESCAPED_UNICODE) : 'فشل: ' . substr($phone->body(), 0, 220));
        }

        $this->info('=== الـ WABA ===');
        $this->line("waba_id=" . ($waba ?: '-'));
        if ($waba !== '') {
            $subs = Http::withToken($token)->get("https://graph.facebook.com/{$version}/{$waba}/subscribed_apps");
            $this->line('subscribed_apps: ' . ($subs->successful() ? json_encode($subs->json(), JSON_UNESCAPED_UNICODE) : 'فشل: ' . substr($subs->body(), 0, 220)));

            $numbers = Http::withToken($token)->get("https://graph.facebook.com/{$version}/{$waba}/phone_numbers", [
                'fields' => 'id,display_phone_number,verified_name,status,quality_rating',
            ]);
            $this->line('phone_numbers: ' . ($numbers->successful() ? json_encode($numbers->json(), JSON_UNESCAPED_UNICODE) : 'فشل: ' . substr($numbers->body(), 0, 220)));

            $templates = Http::withToken($token)->get("https://graph.facebook.com/{$version}/{$waba}/message_templates", [
                'fields' => 'name,status,language,category',
                'limit' => 50,
            ]);
            $this->line('templates: ' . ($templates->successful() ? json_encode($templates->json(), JSON_UNESCAPED_UNICODE) : 'فشل: ' . substr($templates->body(), 0, 220)));
        }

        $this->info('=== القالب المستخدم ===');
        $this->line("template={$template} language=" . config('services.whatsapp_cloud.otp_language'));

        $to = (string) $this->option('to');
        if ($to !== '') {
            $this->info('=== إرسال تجريبي ===');
            $result = app(\App\Services\WhatsAppOtpService::class)->send($to, '135790');
            $this->line(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        return self::SUCCESS;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyOtoApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-OTO-API-Key') ?: $request->header('Authorization');
        
        if (!$apiKey || $apiKey !== config('services.oto.api_key')) {
            return response()->json([
                'success' => false,
                'message' => 'مفتاح API غير صالح',
                'error' => 'INVALID_API_KEY'
            ], 401);
        }
        
        return $next($request);
    }
}
<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * تحقق Meta من الـ Webhook (GET) — بيرجع hub.challenge لو الرمز مطابق.
     */
    public function verify(Request $request)
    {
        $mode = (string) $request->query('hub_mode', $request->query('hub.mode', ''));
        $token = (string) $request->query('hub_verify_token', $request->query('hub.verify_token', ''));
        $challenge = (string) $request->query('hub_challenge', $request->query('hub.challenge', ''));

        $expected = (string) config('services.whatsapp_cloud.webhook_verify_token');

        if ($mode === 'subscribe' && $expected !== '' && hash_equals($expected, $token)) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        Log::warning('WhatsApp webhook verification failed', [
            'mode' => $mode,
            'ip' => $request->ip(),
        ]);

        return response('Forbidden', 403);
    }

    /**
     * استقبال أحداث واتساب (رسائل / حالات تسليم / تحديثات قوالب).
     */
    public function receive(Request $request)
    {
        $raw = $request->getContent();
        $secret = (string) config('services.whatsapp_cloud.app_secret');
        $signature = (string) $request->header('X-Hub-Signature-256');

        if ($secret !== '') {
            $expected = 'sha256=' . hash_hmac('sha256', $raw, $secret);

            if ($signature === '' || ! hash_equals($expected, $signature)) {
                Log::warning('WhatsApp webhook signature mismatch', ['ip' => $request->ip()]);

                return response('Invalid signature', 403);
            }
        } else {
            // لسه مفيش App Secret في الإعدادات — بنسجل ونكمل بدون تحقق.
            Log::warning('WhatsApp webhook received without app secret configured.');
        }

        $payload = json_decode($raw, true) ?: [];

        $this->appendToLog($payload);
        $this->applyStatuses($payload);

        return response('EVENT_RECEIVED', 200);
    }

    /**
     * تحديث حالة الرسائل المسجلة عندنا بحسب أحداث ميتا.
     */
    protected function applyStatuses(array $payload): void
    {
        foreach (($payload['entry'] ?? []) as $entry) {
            foreach (($entry['changes'] ?? []) as $change) {
                foreach (($change['value']['statuses'] ?? []) as $status) {
                    $wamid = $status['id'] ?? null;

                    if (! $wamid) {
                        continue;
                    }

                    $message = \App\Models\WhatsAppMessage::where('wamid', $wamid)->first();

                    if ($message) {
                        $message->applyMetaStatus((string) ($status['status'] ?? ''), $status['errors'] ?? null);
                    } else {
                        Log::warning('WhatsApp webhook status for unknown wamid', [
                            'wamid' => $wamid,
                            'status' => $status['status'] ?? null,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * نسجل كل حدث في ملف منفصل لتسهيل المتابعة.
     */
    protected function appendToLog(array $payload): void
    {
        try {
            $path = storage_path('logs/whatsapp-webhook.log');
            $line = now()->toDateTimeString() . ' ' . json_encode($payload, JSON_UNESCAPED_UNICODE) . PHP_EOL;
            @file_put_contents($path, $line, FILE_APPEND | LOCK_EX);
        } catch (\Throwable $e) {
            Log::error('WhatsApp webhook log write failed: ' . $e->getMessage());
        }
    }
}

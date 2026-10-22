<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WhatsAppOtpService
{
    /**
     * إرسال رمز OTP عبر WhatsApp Cloud API (نفس تكامل متجر Moya).
     * في حال عدم إضافة بيانات الاعتماد محليًا يتم تسجيل الرمز في اللوج
     * حتى يمكن اختبار الفلو بدون إرسال رسالة فعلية.
     */
    public function send(string $phone, string $code): array
    {
        $accessToken = config('services.whatsapp_cloud.access_token');
        $phoneNumberId = config('services.whatsapp_cloud.phone_number_id');
        $apiVersion = config('services.whatsapp_cloud.api_version', 'v25.0');

        if (blank($accessToken) || blank($phoneNumberId)) {
            if (app()->environment(['local', 'testing'])) {
                Log::info('WhatsApp OTP (development fallback)', [
                    'phone' => $this->normalizePhoneNumber($phone),
                    'otp' => $code,
                ]);

                return [
                    'success' => true,
                    'logged' => true,
                    'sid' => null,
                    'status' => 'logged',
                ];
            }

            return [
                'success' => false,
                'error' => 'WhatsApp Cloud API credentials are not configured.',
            ];
        }

        try {
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->timeout(20)
                ->post("https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->normalizePhoneNumber($phone),
                    'type' => 'template',
                    'template' => [
                        'name' => config('services.whatsapp_cloud.otp_template', 'waitmeyah_otp'),
                        'language' => [
                            'code' => config('services.whatsapp_cloud.otp_language', 'ar'),
                        ],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    ['type' => 'text', 'text' => $code],
                                ],
                            ],
                            [
                                'type' => 'button',
                                'sub_type' => 'url',
                                'index' => '0',
                                'parameters' => [
                                    ['type' => 'text', 'text' => $code],
                                ],
                            ],
                        ],
                    ],
                ]);

            $data = $response->json();

            if ($response->successful() && ! empty($data['messages'][0]['id'])) {
                return [
                    'success' => true,
                    'sid' => $data['messages'][0]['id'],
                    'status' => $data['messages'][0]['message_status'] ?? 'accepted',
                ];
            }

            $error = $data['error']['message'] ?? 'Failed to send WhatsApp OTP';

            Log::warning('WhatsApp Cloud OTP sending failed.', [
                'to' => $this->normalizePhoneNumber($phone),
                'status_code' => $response->status(),
                'meta_error_code' => $data['error']['code'] ?? null,
                'meta_error_subcode' => $data['error']['error_subcode'] ?? null,
                'error' => $error,
            ]);

            return ['success' => false, 'error' => $error];
        } catch (\Throwable $e) {
            Log::error('WhatsApp Cloud OTP request failed.', [
                'to' => $this->normalizePhoneNumber($phone),
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * الرقم بصيغة دولية بدون علامات: 9665XXXXXXXX
     */
    public function normalizePhoneNumber(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '00966')) {
            $digits = substr($digits, 5);
        } elseif (str_starts_with($digits, '966')) {
            $digits = substr($digits, 3);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return '966' . $digits;
    }
}

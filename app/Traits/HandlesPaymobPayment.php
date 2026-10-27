<?php

namespace App\Traits;

use Log;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait HandlesPaymobPayment
{
    /**
     * بدء عملية الدفع عبر PayMob KSA باستخدام Payment Links (الطريقة الجديدة والموصى بها)
     */
    public function initiatePaymobPayment($order)
    {
        // 1. Authentication
        $auth = $this->paymobAuthenticate();
        if (!$auth['success']) {
            return response()->json($auth, 400);
        }

        // 2. Create Payment Link
        $paymentLink = $this->createPaymobPaymentLink($auth['token'], $order);

        if (!$paymentLink['success']) {
            return [
                'success' => false,
                'message' => 'فشل إنشاء رابط الدفع',
                'error_details' => $paymentLink, // إظهار كل تفاصيل الخطأ
            ];
        }


        // نجح كل شيء → نرجع رابط الدفع للموبايل
        return [
            'success'      => true,
            'payment_url'  => $paymentLink['payment_url'],
            'shorten_url'  => $paymentLink['shorten_url'] ?? null,
            'order_number' => $order->order_number,
            'message'      => 'تم إنشاء رابط الدفع بنجاح'
        ];
    }

    /**
     * تسجيل الدخول وجلب التوكن
     */
    private function paymobAuthenticate(): array
    {
        // الحسابات الجديدة في Paymob بتستخدم API Key (JWT) بدل username/password.
        $apiKey = config('services.paymob.api_key');

        $payload = $apiKey
            ? ['api_key' => $apiKey]
            : [
                'username' => config('services.paymob.username'),
                'password' => config('services.paymob.password'),
            ];

        $response = Http::post('https://ksa.paymob.com/api/auth/tokens', $payload);

        if ($response->failed()) {
            return [
                'success' => false,
                'message' => 'فشل تسجيل الدخول في PayMob',
                'error'   => $response->json(),
            ];
        }

        return [
            'success' => true,
            'token'   => $response->json('token'),
        ];
    }

    /**
     * دومين المتجر اللي العميل بيرجع له بعد الدفع.
     */
    private function paymobFrontendUrl(): string
    {
        return rtrim((string) (config('services.paymob.frontend_url') ?: 'https://talaaljazeera.com'), '/');
    }

    /**
     * Paymob KSA بتقبل رقم الجوال بصيغة دولية +9665XXXXXXXX فقط،
     * وأي صيغة تانية (05… أو 966… أو 5…) بترجع "Invalid phone number".
     */
    private function paymobPhoneNumber(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        if (str_starts_with($digits, '00966')) {
            $digits = substr($digits, 5);
        } elseif (str_starts_with($digits, '966')) {
            $digits = substr($digits, 3);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return '+966' . $digits;
    }

    /**
     * إنشاء رابط دفع PayMob (الطريقة الصحيحة الجديدة)
     */
    private function createPaymobPaymentLink($token, $order)
    {
        $amountInCents = max(100, (int) round(($order->total_amount ?? 0) * 100));

        $response = Http::withToken($token)
            ->asForm()
            ->post('https://ksa.paymob.com/api/ecommerce/payment-links', [
                'amount_cents'     => $amountInCents,
                'currency'         => 'SAR',
                'reference_id'     => $order->order_number,
                'payment_methods'  => [config('services.paymob.integration_id')],
                'full_name'        => $order->customer_name,
                'email'            => $order->customer_email ?? 'customer@example.com',
                'phone_number'     => $this->paymobPhoneNumber($order->customer_phone),
                'expires_at'       => now()->setTimezone('Asia/Riyadh')->addHours(2)->format('Y-m-d\TH:i:s'),
                'save_selection'   => true,
                'is_live'          => config('services.paymob.mode') === 'live',

                // الأسماء دي هي اللي Paymob بتقراها فعلاً (redirect_url/callback_url
                // كانت بتتجاهلها فماكنش فيه لا رجوع للمتجر ولا Webhook).
                'redirection_url' => $this->paymobFrontendUrl() . "/payment-status?status=success&orderId={$order->order_number}",
                'notification_url' => url('/api/v1/paymob/webhook'),
            ]);
        if ($response->failed()) {
            return [
                'success' => false,
                'message' => 'فشل إنشاء رابط الدفع',
                'error'   => $response->json(),
            ];
        }
        $data = $response->json();

        return [
            'success'      => true,
            'payment_url'  => $data['client_url'] ?? $data['shorten_url'] ?? null,
            'shorten_url'  => $data['shorten_url'] ?? null,

        ];
    }

    /**
     * التحقق من HMAC للـ Webhook
     */
    public function verifyPaymobHmac(array $payload): bool
    {
        $hmac = (string) ($payload['hmac'] ?? '');
        $obj = $payload['obj'] ?? null;
        $secret = (string) config('services.paymob.hmac_secret');

        if ($hmac === '' || $secret === '' || ! is_array($obj)) {
            return false;
        }

        // Paymob بتحسب الـ HMAC على ترتيب ثابت من الحقول (مش كل الحقول مرتبة أبجديًا).
        $bool = fn ($value) => is_bool($value) ? ($value ? 'true' : 'false') : (string) ($value ?? '');

        $concatenated = implode('', [
            $obj['amount_cents'] ?? '',
            $obj['created_at'] ?? '',
            $obj['currency'] ?? '',
            $bool($obj['error_occured'] ?? null),
            $bool($obj['has_parent_transaction'] ?? null),
            $obj['id'] ?? '',
            $obj['integration_id'] ?? '',
            $bool($obj['is_3d_secure'] ?? null),
            $bool($obj['is_auth'] ?? null),
            $bool($obj['is_capture'] ?? null),
            $bool($obj['is_refunded'] ?? null),
            $bool($obj['is_standalone_payment'] ?? null),
            $bool($obj['is_voided'] ?? null),
            $obj['order']['id'] ?? '',
            $obj['owner'] ?? '',
            $bool($obj['pending'] ?? null),
            $obj['source_data']['pan'] ?? '',
            $obj['source_data']['sub_type'] ?? '',
            $obj['source_data']['type'] ?? '',
            $bool($obj['success'] ?? null),
        ]);

        return hash_equals(hash_hmac('sha512', $concatenated, $secret), $hmac);
    }

    /**
     * معالجة Webhook من PayMob
     */
    public function handlePaymobWebhook(Request $request)
    {
        if (!$this->verifyPaymobHmac($request->all())) {
            return response('Invalid HMAC', 400);
        }

        $type = $request->input('type');
        $obj  = $request->input('obj');

        // دفع ناجح + تم السحب (Capture)
        if ($type === 'TRANSACTION' && $obj['success'] === true && $obj['is_capture'] === true) {

            // مرجع الأوردر بيختلف حسب نوع العملية، فبنجرب كل الأماكن المحتملة.
            $orderNumber = $obj['merchant_reference']
                ?? $obj['order']['merchant_order_id']
                ?? $obj['payment_link']['reference_id']
                ?? null;

            if (!$orderNumber) {
                \Illuminate\Support\Facades\Log::error('PayMob Webhook: No merchant_reference', $obj);
                return response('No reference', 400);
            }

            $order = Order::where('order_number', $orderNumber)->first();

            if ($order && $order->status === 'pending') {
                $order->update([
                    'status'         => 'paid',
                    'payment_method' => 'paymob',
                    'transaction_id' => $obj['id'],
                    'paid_at'         => now(),
                ]);

                // هنا نفذ كل حاجة: تفريغ المخزون، إرسال إيميل، إشعار واتساب، إلخ
            }
        }

        return response('OK', 200);
    }
}

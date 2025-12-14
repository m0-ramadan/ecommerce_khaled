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
        $response = Http::post('https://ksa.paymob.com/api/auth/tokens', [
            'username' => config('services.paymob.username'),
            'password' => config('services.paymob.password'),
        ]);

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
                'phone_number'     => $order->customer_phone,
                'expires_at'       => now()->setTimezone('Asia/Riyadh')->addHours(2)->format('Y-m-d\TH:i:s'),
                'save_selection'   => true,
                'is_live'          => config('services.paymob.mode') === 'live',

                // URLs مهمة جدًا
                //   'redirect_url'     => url("/payment/success/{$order->order_number}"),
                'redirect_url' => url("/payment-status?status=success&orderId={$order->order_number}"),
                'cancel_url'   => url("/payment-status?status=failed&orderId={$order->order_number}"),

                // Webhook بيجي من الداشبورد، بس ممكن تبعته كمان
                'callback_url' => url('/api/v1/paymob/webhook'),
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
        $hmac = $payload['hmac'] ?? '';
        unset($payload['hmac']);

        ksort($payload);
        $concatenated = collect($payload)->flatten()->implode('');
        $secret = config('services.paymob.hmac_secret');

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

            // الحقل الصح في KSA هو merchant_reference
            $orderNumber = $obj['merchant_reference'] ?? null;

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

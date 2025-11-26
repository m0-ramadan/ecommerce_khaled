<?php

namespace App\Traits;

use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


trait HandlesPaymobPayment
{
    /**
     * بدء عملية الدفع عبر PayMob
     */
    public function initiatePaymobPayment($order, $returnUrl = null)
    {
        $auth = $this->paymobAuthenticate();

        if (!$auth['success']) {
            return ['success' => false, 'message' => 'فشل الاتصال ببوابة الدفع'];
        }

        $token = $auth['token'];

        // 1. تسجيل الطلب في PayMob
        $orderRegistration = $this->paymobRegisterOrder($token, $order);

        if (!$orderRegistration['success']) {
            return $orderRegistration;
        }

        $paymentKey = $this->paymobGetPaymentKey(
            token: $token,
            orderId: $orderRegistration['order_id'],
            amountCents: $order->total_amount * 100,
            billingData: $this->getBillingData($order)
        );

        if (!$paymentKey['success']) {
            return $paymentKey;
        }

        // رابط الـ iframe للدفع
        $iframeUrl = "https://accept.paymob.com/api/acceptance/iframes/" . config('services.paymob.iframe_id') . "?payment_token=" . $paymentKey['token'];

        return [
            'success' => true,
            'payment_url' => $iframeUrl,
            'order_number' => $order->order_number,
            'message' => 'جاري توجيهك إلى بوابة الدفع...'
        ];
    }

    private function paymobAuthenticate(): array
    {
        $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => config('services.paymob.api_key'),
        ]);

        if ($response->failed()) {
            return ['success' => false, 'message' => 'فشل الاتصال بـ PayMob'];
        }

        return [
            'success' => true,
            'token' => $response->json('token')
        ];
    }

    private function paymobRegisterOrder(string $token, $order): array
    {
        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
            'auth_token' => $token,
            'delivery_needed' => false,
            'amount_cents' => $order->total_amount * 100,
            'currency' => 'EGP',
            'items' => [],
            'merchant_order_id' => $order->id,
        ]);

        if ($response->failed()) {
            return ['success' => false, 'message' => 'فشل تسجيل الطلب في PayMob'];
        }

        return [
            'success' => true,
            'order_id' => $response->json('id')
        ];
    }

    private function paymobGetPaymentKey(string $token, int $orderId, int $amountCents, array $billingData): array
    {
        $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
            'auth_token' => $token,
            'amount_cents' => $amountCents,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => 'EGP',
            'integration_id' => config('services.paymob.integration_id'),
        ]);

        if ($response->failed()) {
            return ['success' => false, 'message' => 'فشل إنشاء مفتاح الدفع'];
        }

        return [
            'success' => true,
            'token' => $response->json('token')
        ];
    }

    private function getBillingData($order): array
    {
        return [
            "apartment" => "NA",
            "email" => $order->customer_email ?? "customer@example.com",
            "floor" => "NA",
            "first_name" => explode(' ', $order->customer_name)[0] ?? 'Customer',
            "street" => $order->shipping_address ?? 'NA',
            "building" => "NA",
            "phone_number" => $order->customer_phone,
            "shipping_method" => "PKG",
            "postal_code" => "00000",
            "city" => "Cairo",
            "country" => "EG",
            "last_name" => explode(' ', $order->customer_name)[1] ?? 'User',
            "state" => "Cairo"
        ];
    }

    /**
     * التحقق من الـ HMAC (Webhook)
     */
    public function verifyPaymobHmac(array $payload): bool
    {
        $hmac = $payload['hmac'] ?? '';
        unset($payload['hmac']);

        ksort($payload);
        $concatenated = collect($payload)->flatten()->implode('');

        $calculatedHmac = hash_hmac('sha512', $concatenated, config('services.paymob.hmac_secret'));

        return hash_equals($calculatedHmac, $hmac);
    }

    /**
     * معالجة الـ Webhook من PayMob
     */
    public function handlePaymobWebhook(Request $request)
    {
        if (!$this->verifyPaymobHmac($request->all())) {
            return response('Invalid HMAC', 400);
        }

        $type = $request->input('type');
        $obj = $request->input('obj');

        if ($type === 'TRANSACTION' && $obj['success'] === true && $obj['is_capture']) {
            $orderId = $obj['order']['merchant_order_id'];
            $transactionId = $obj['id'];

            $order = Order::find($orderId);
            if ($order && $order->status === 'pending') {
                $order->update([
                    'status' => 'paid',
                    'payment_method' => 'credit_card',
                    'transaction_id' => $transactionId,
                ]);

                // إرسال إشعار، إيميل، إلخ...
            }
        }

        return response('OK', 200);
    }
}
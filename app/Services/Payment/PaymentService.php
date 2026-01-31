<?php

namespace App\Services\Payment;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderOffer;
use App\Models\PaymentMethod;
use App\Services\Wallet\UserWalletService;
use App\Services\Payment\Factories\PaymentGatewayFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\PaymentSuccessful;
use App\Notifications\OrderPaid;
use Faker\Provider\Payment;

class PaymentService
{
    private PaymentGatewayFactory $gatewayFactory;

    public function __construct(
        PaymentGatewayFactory $gatewayFactory
    ) {
        $this->gatewayFactory = $gatewayFactory;
    }

    public function processOrderPayment(
        ?User $user,
        Order $order,
        string $gateway,
        string $paymentMethod,
        array $cartItems = [],
        int $shippingPrice = null
    ): array {
        DB::beginTransaction();

        try {
            // تحضير بيانات الطلب للبوابة
            $orderData = $this->prepareOrderData($order, $user, $gateway, $cartItems);

            // إنشاء بوابة الدفع المناسبة
            $paymentGateway = $this->gatewayFactory->make($gateway);

            // بدء عملية الدفع
            $result = $paymentGateway->initiatePayment($orderData);

            if (!$result['success']) {
                throw new \Exception($result['error'] ?? $result['message'] ?? 'Payment failed');
            }
            // حفظ بيانات الدفع في الطلب
            $this->savePaymentData($order, $gateway, $paymentMethod, $result);

            DB::commit();

            return [
                'success' => true,
                'message' => 'تم بدء عملية الدفع بنجاح',
                'payment_url' => $result['payment_url'] ?? $result['checkout_url'] ?? null,
                'shorten_url' => $result['shorten_url'] ?? null,
                'order_number' => $order->order_number,
                'gateway' => $gateway,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('payment')->error('Payment Processing Failed', [
                'user_id' => $user?->id,
                'order_id' => $order->id,
                'gateway' => $gateway,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'PAYMENT_PROCESSING_FAILED',
            ];
        }
    }

    private function prepareOrderData(
        Order $order,
        ?User $user,
        string $gateway,
        array $cartItems
    ): array {
        // تحضير البيانات حسب البوابة
        $baseData = [
            'order_id' => $order->order_number,
            'amount' => $order->total_amount,
            'user' => $user,
            'customer' => [
                'first_name' => $order->customer_name ?? $user?->name,
                'last_name' => '',
                'email' => $order->customer_email ?? $user?->email ?? 'customer@example.com',
                'phone' => $order->customer_phone ?? $user?->phone ?? '+966500000000',
            ],
            // 'callback_url' => $this->getCallbackUrl($gateway, $order->id),
        ];

        // إضافة بيانات إضافية حسب البوابة
        switch ($gateway) {
            case 'tamara':
            case 'tabby':
                $baseData['items'] = $this->prepareItemsFromCart($cartItems);
                $baseData['shipping_address'] = $this->getShippingAddress($order);
                $baseData['billing_address'] = $this->getBillingAddress($order);
                break;
        }

        return $baseData;
    }

    private function prepareItemsFromCart(array $cartItems): array
    {
        $items = [];
        foreach ($cartItems as $item) {
            $items[] = [
                'name' => $item['product']['name'] ?? 'منتج',
                'description' => $item['product']['description'] ?? 'منتج',
                'quantity' => $item['quantity'] ?? 1,
                'unit_price' => $item['price_per_unit'] ?? 1,
                'total_price' => $item['total_price']
                    ?? (($item['price_per_unit'] ?? 1) * ($item['quantity'] ?? 1)),
                'sku' => $item['product']['sku'] ?? 'PROD-' . ($item['product_id'] ?? '000'),
            ];
        }

        return $items;
    }

    private function getShippingAddress(Order $order): array
    {
        return [
            'first_name' => $order->customer_name ?? 'العميل',
            'last_name' => '',
            'address_line1' => $order->shipping_address ?? 'غير محدد',
            'city' => 'الرياض',
            'region' => 'الرياض',
            'country_code' => 'SA',
            'postal_code' => '',
            'phone' => $order->customer_phone ?? '+966500000000',
        ];
    }

    private function getBillingAddress(Order $order): array
    {
        return $this->getShippingAddress($order);
    }




    private function savePaymentData(Order $order, string $gateway, string $paymentMethod, array $paymentResult): void
    {
        $order->update([
            'payment_gateway' => $gateway,
            'transaction_id' => $paymentResult['payment_id'] ?? $paymentResult['order_id'] ?? null,
            'payment_status' => 'pending',
            'payment_details' => array_merge(
                $order->payment_details ?? [],
                [
                    'gateway' => $gateway,
                    'method' => $paymentMethod,
                    'initiated_at' => now(),
                    'payment_data' => $paymentResult,
                ]
            ),
        ]);
    }


    private function completePayment(Order $order, array $paymentResult): void
    {
        DB::beginTransaction();

        try {
            // تحديث حالة الطلب
            $order->update([
                'payment_status' => Order::PAYMENT_STATUS_PAID,
                'paid_at' => now(),
                'payment_details' => array_merge(
                    $order->payment_details ?? [],
                    [
                        'verified_at' => now(),
                        'verification_data' => $paymentResult,
                        'status' => 'completed',
                    ]
                ),
            ]);

            // تحديث حالة العرض إلى "مدفوع"
            $offer = $order->offers()->where('driver_id', $order->driver_id)->first();
            if ($offer) {
                $offer->update(['status' => 'paid']);
            }

            // إرسال الإشعارات
            $this->sendPaymentNotifications($order);

            DB::commit();

            Log::channel('payment')->info('Payment Completed Successfully', [
                'order_id' => $order->id,
                'gateway' => $order->payment_gateway,
                'amount' => $order->getPaymentAmount(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    private function processWebhookEvent(string $gateway, array $webhookData): void
    {
        $orderId = $webhookData['order_id'] ?? null;
        $status = $webhookData['status'] ?? null;

        if (!$orderId) {
            return;
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::channel('payment')->warning('Order not found for webhook', [
                'order_id' => $orderId,
                'gateway' => $gateway,
            ]);
            return;
        }

        switch ($status) {
            case 'approved':
            case 'captured':
            case 'success':
                $this->completePayment($order, $webhookData);
                break;

            case 'declined':
            case 'failed':
                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_FAILED,
                    'payment_details' => array_merge(
                        $order->payment_details ?? [],
                        [
                            'failed_at' => now(),
                            'failure_reason' => $webhookData['error'] ?? 'Payment declined',
                        ]
                    ),
                ]);
                break;

            case 'refunded':
                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_REFUNDED,
                    'payment_details' => array_merge(
                        $order->payment_details ?? [],
                        [
                            'refunded_at' => now(),
                            'refund_data' => $webhookData,
                        ]
                    ),
                ]);
                break;
        }
    }


    private function sendPaymentNotifications(Order $order): void
    {
        try {
            // إشعار للمستخدم
            if ($order->user) {
                $order->user->notify(new PaymentSuccessful($order));
            }
        } catch (\Exception $e) {
            Log::channel('payment')->error('Failed to send payment notifications', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

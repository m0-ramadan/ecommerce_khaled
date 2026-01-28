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
        array $cartItems = []
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
                // $baseData['callback_urls'] = [
                //     'success' => route('payment.callback.success', ['gateway' => $gateway, 'order_id' => $order->id]),
                //     'failure' => route('payment.callback.failure', ['gateway' => $gateway, 'order_id' => $order->id]),
                //     'cancel' => route('payment.callback.cancel', ['gateway' => $gateway, 'order_id' => $order->id]),
                // ];
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

private function getCallbackUrl(string $gateway, int $orderId): string
{
    return route(
        'payment.callback.' . $gateway,
        ['orderId' => $orderId] 
    );
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
 

    private function getOrderItems(Order $order): array
    {
        return [
            [
                'name' => $order->service->name ?? 'Water Delivery Service',
                'description' => 'Water delivery to your location',
                'quantity' => 1,
                'unit_price' => $order->price,
                'total_price' => $order->price,
                'sku' => 'SERVICE-' . $order->service_id,
            ]
        ];
    }


    public function verifyPayment(Order $order): array
    {
        try {
            if (!$order->payment_gateway || !$order->payment_transaction_id) {
                throw new \Exception('No payment information found');
            }

            if ($order->payment_gateway === 'wallet') {
                return [
                    'success' => true,
                    'status' => 'paid',
                    'gateway' => 'wallet',
                    'verified' => true,
                ];
            }

            $paymentGateway = $this->gatewayFactory->make($order->payment_gateway);

            $verificationData = [
                'payment_id' => $order->payment_transaction_id,
                'order_id' => $order->id,
            ];

            $result = $paymentGateway->verifyPayment($verificationData);

            if ($result['success'] && in_array($result['status'], ['captured', 'approved', 'success'])) {
                $this->completePayment($order, $result);
            }

            return $result;
        } catch (\Exception $e) {
            Log::channel('payment')->error('Payment Verification Failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'PAYMENT_VERIFICATION_FAILED',
            ];
        }
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

    public function handleWebhook(string $gateway, array $data): array
    {
        try {
            $paymentGateway = $this->gatewayFactory->make($gateway);

            if (!$paymentGateway->isWebhookValid($data)) {
                throw new \Exception('Invalid webhook signature');
            }

            $result = $paymentGateway->handleWebhook($data);

            if ($result['success'] && $result['handled']) {
                $this->processWebhookEvent($gateway, $result);
            }

            return $result;
        } catch (\Exception $e) {
            Log::channel('payment')->error('Webhook Processing Failed', [
                'gateway' => $gateway,
                'data' => $data,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'WEBHOOK_PROCESSING_FAILED',
            ];
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

    public function refundPayment(Order $order, string $reason = ''): array
    {
        try {
            if (!$order->isPaid()) {
                throw new \Exception('Order is not paid');
            }

            if ($order->payment_gateway === 'wallet') {
                $result = $this->refundWalletPayment($order, $reason);
            } else {
                $paymentGateway = $this->gatewayFactory->make($order->payment_gateway);
                $result = $paymentGateway->refundPayment(
                    $order->payment_transaction_id,
                    $order->getPaymentAmount(),
                    $reason
                );
            }

            if ($result['success']) {
                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_REFUNDED,
                    'payment_details' => array_merge(
                        $order->payment_details ?? [],
                        [
                            'refunded_at' => now(),
                            'refund_reason' => $reason,
                            'refund_data' => $result,
                        ]
                    ),
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::channel('payment')->error('Refund Processing Failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'REFUND_PROCESSING_FAILED',
            ];
        }
    }

    private function refundWalletPayment(Order $order, string $reason)
    {
        $walletEntry = $this->walletService->deposit($order->user, $order->getPaymentAmount(), [
            'description' => 'Refund for Order #' . $order->order_number,
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'refund_reason' => $reason,
            ]
        ]);

        return [
            'success' => true,
            'transaction_id' => 'REFUND-WALLET-' . $walletEntry->id,
            'amount' => $order->getPaymentAmount(),
            'gateway' => 'wallet',
            'message' => 'Refund processed to wallet',
        ];
    }

    public function getAvailableGateways(): array
    {
        return $this->gatewayFactory->getAvailableGateways();
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

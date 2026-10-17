<?php

namespace App\Services\Payment\Gateways;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\Payment\Gateways\BaseGateway;

class TamaraGateway extends BaseGateway
{
    protected function initializeConfig(): void
    {
        $this->config = config('services.tamara', []);
        $this->currency = config('services.tamara.currency', 'SAR');
        $this->isSandbox = config('services.tamara.sandbox', true);
    }

    protected function getGatewayName(): string
    {
        return 'tamara';
    }

    protected function getBaseUrl(): string
    {
        return $this->isSandbox
            ? 'https://api-sandbox.tamara.co'
            : 'https://api.tamara.co';
    }

    protected function fetchAuthToken(): string
    {
        $apiToken = $this->config['api_token'] ?? '';

        if (!$apiToken) {
            throw new \Exception('Tamara API token is not configured');
        }

        return $apiToken;
    }

    public function createPaymentOrder(array $data): array
    {
        return $this->initiatePayment($data);
    }

    public function initiatePayment(array $data): array
    {
        try {
            // التحقق من البيانات المطلوبة
            $required = ['order_id', 'amount', 'customer'];
            if (!$this->validateRequired($data, $required)) {
                throw new \Exception('Missing required payment data');
            }

            $authToken = $this->fetchAuthToken();

            // تحضير بيانات طلب الدفع
            $checkoutData = $this->prepareCheckoutData($data);

            Log::channel('payment')->debug('Tamara checkout payload', [
                'payload' => $checkoutData,
            ]);

            // استخدام makeRequest من BaseGateway
            $response = $this->makeRequest(
                'POST',
                '/checkout',
                $checkoutData,
                ['Authorization' => "Bearer {$authToken}"]
            );

            if (!$response['success']) {
                Log::channel('payment')->error('Tamara API Error Response', [
                    'error' => $response['error'] ?? 'Unknown error',
                    'status' => $response['status'] ?? null,
                    'raw_response' => $response['raw_response'] ?? null,
                ]);
                throw new \Exception($response['error'] ?? 'Failed to create Tamara checkout session');
            }

            $responseData = $response['data'];

            return [
                'success' => true,
                'gateway' => 'tamara',
                'order_id' => $responseData['order_id'] ?? null,
                'checkout_id' => $responseData['checkout_id'] ?? null,
                'checkout_url' => $responseData['checkout_url'] ?? null,
                'status' => $responseData['status'] ?? 'new',
                'expires_at' => now()->addMinutes(30)->toIso8601String(), // Tamara default expiry is 30 minutes
                'raw_response' => $responseData,
            ];
        } catch (\Exception $e) {
            Log::channel('payment')->error('Tamara Payment Initiation Failed', [
                'order_id' => $data['order_id'] ?? null,
                'amount' => $data['amount'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'gateway' => 'tamara',
                'error' => $e->getMessage(),
                'error_code' => 'TAMARA_INIT_FAILED',
            ];
        }
    }

    private function prepareCheckoutData(array $data): array
    {
        $order = Order::with(['user', 'address', 'orderItems.product'])
            ->where('order_number', $data['order_id'])
            ->firstOrFail();
        $user  = $order->user;
        $address = $order->address;
        $items = $order->orderItems;

        // ==================== تجهيز العناصر ====================
        $orderItems = $items->map(function (OrderItem $item) {
            $unitPrice = (float) $item->price_per_unit;
            $quantity  = (int) $item->quantity;
            $totalAmount = (float) $item->total_price ?? $unitPrice * $quantity;

            return [
                'reference_id' => 'ITEM-' . $item->id,
                'type'         => ($item->category ?? 'physical') === 'services' ? 'digital' : 'physical',
                'name'         => $item->product?->name ?? 'Product',
                'sku'          => $item->product?->sku ?? 'SKU-' . $item->id,
                'quantity'     => $quantity,
                'unit_price'   => [
                    'amount' => $unitPrice,
                    'currency' => $this->currency,
                ],
                'total_amount' => [
                    'amount' => $totalAmount,
                    'currency' => $this->currency,
                ],
                'tax_amount' => [
                    'amount' => (float) $item->tax_amount ?? 0,
                    'currency' => $this->currency,
                ],
                'discount_amount' => [
                    'amount' => (float) $item->discount_amount ?? 0,
                    'currency' => $this->currency,
                ],
                'item_url' => $item->product?->product_url ?? null,
                'image_url' => $item->product?->image ?? null,
            ];
        })->values()->toArray();

        // ==================== المبالغ الكلية ====================
        $totalAmount = (float) $order->total_amount;
        $shippingAmount = (float) $order->shipping_amount;
        $taxAmount = (float) $order->tax_amount;
        $discountAmount = (float) $order->discount_amount;

        $riskAssessment = $this->prepareRiskAssessment($user, $order);
        $customerName = $order->customer_name ?? $user?->name ?? 'Customer';
        $customerEmail = $order->customer_email ?? $user?->email;
        $customerPhone = $order->customer_phone ?? $user?->phone ?? '+966500000000';
        $addressLine = $address?->address_details ?? $order->shipping_address ?? 'N/A';
        $city = $address?->city ?? 'Riyadh';
        $region = $address?->area ?? $city;

        return [
            'order_reference_id' => (string) $order->id,
            'order_number' => $order->order_number,
            'total_amount' => [
                // total_amount في Order يحتوي بالفعل على رسوم الشحن والخصم.
                'amount' => $totalAmount,
                'currency' => $this->currency,
            ],
            'shipping_amount' => [
                'amount' => $shippingAmount,
                'currency' => $this->currency,
            ],
            'tax_amount' => [
                'amount' => $taxAmount,
                'currency' => $this->currency,
            ],
            'items' => $orderItems,
            'consumer' => [
                'first_name' => $customerName,
                'last_name' => '',
                'email' => $customerEmail,
                'phone_number' => $this->formatPhoneNumber($customerPhone),
                'is_first_order' => $user ? $user->orders()->count() === 1 : true,
            ],
            'country_code' => 'SA',
            'description' => 'Order #' . $order->order_number,
            'merchant_url' => [
                'success' => route('payment.tamara.success'),
                'failure' => route('payment.tamara.failure'),
                'cancel'  => route('payment.tamara.cancel'),
                'notification' => route('payment.tamara.webhook'),
            ],
            'shipping_address' => [
                'first_name' => $customerName,
                'last_name' => '',
                'line1' => $addressLine,
                'line2' => '',
                'city' => $city,
                'country_code' => 'SA',
                'region' => $region,
                'phone_number' => $this->formatPhoneNumber($customerPhone),
            ],
            'billing_address' => [
                'first_name' => $customerName,
                'last_name' => '',
                'line1' => $addressLine,
                'line2' => '',
                'city' => $city,
                'country_code' => 'SA',
                'region' => $region,
                'phone_number' => $this->formatPhoneNumber($customerPhone),
            ],
            'platform' => 'API',
            'locale' => 'ar_SA',
            'risk_assessment' => $riskAssessment,
            'additional_data' => [
                'delivery_method' => 'Home Delivery',
                'vendor_amount' => 0,
                'merchant_settlement_amount' => $totalAmount,
                'vendor_reference_code' => 'VENDOR-' . $order->id,
            ],
        ];
    }

    /**
     * يحول أي قيمة تاريخية إلى Carbon بصيغة YYYY-MM-DD بشكل آمن
     */
    private function safeParseDate(mixed $date): \Carbon\Carbon
    {
        try {
            return \Carbon\Carbon::parse($date);
        } catch (\Exception $e) {
            return now();
        }
    }
    private function prepareRiskAssessment(?User $customer, Order $currentOrder): array
    {
        $orders = $customer?->orders()->whereKeyNot($currentOrder->id);
        $orderCount = $orders ? (clone $orders)->count() : 0;
        $completedOrders = $orders
            ? (clone $orders)->whereIn('status', ['completed', 'delivered'])->count()
            : 0;
        $totalSpent = $orders
            ? (float) (clone $orders)->whereIn('status', ['completed', 'delivered'])->sum('total_amount')
            : 0.0;
        $recentOrders = $orders
            ? (clone $orders)->where('created_at', '>=', now()->subMonths(3))
            : null;
        $lastOrder = $orders ? (clone $orders)->latest()->first() : null;

        return [
            'customer_age' => (int) ($customer?->age ?? 25),
            'customer_gender' => $customer?->gender ?? 'Male',
            'customer_nationality' => 'SA',
            'is_premium_customer' => false,
            'is_existing_customer' => $orderCount > 0,
            'is_guest_user' => $customer === null,
            'is_card_on_file' => false,
            'is_COD_customer' => false,
            'has_delivered_order' => $completedOrders > 0,
            'is_phone_verified' => !empty($customer?->phone),
            'is_fraudulent_customer' => false,
            'total_ltv' => $totalSpent,
            'total_order_count' => $orderCount,
            'order_amount_last3months' => $recentOrders ? (float) (clone $recentOrders)->sum('total_amount') : 0.0,
            'order_count_last3months' => $recentOrders ? (clone $recentOrders)->count() : 0,
            'last_order_amount' => (float) ($lastOrder?->total_amount ?? 0),
            'reward_program_enrolled' => false,
            'reward_program_points' => 0,
        ];
    }

    /**
     * يحول أي قيمة تاريخية إلى نص YYYY-MM-DD
     */
    private function safeParseDateString(mixed $date): string
    {
        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return now()->format('Y-m-d');
        }
    }



    private function formatPhoneNumber(string $phone): string
    {
        // إزالة أي أحرف غير رقمية
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // إذا كان الرقم يحتوي على 966 في البداية
        if (strlen($phone) === 12 && str_starts_with($phone, '966')) {
            return $phone;
        }

        // إذا كان الرقم 9 أرقام (بدون 966)
        if (strlen($phone) === 9) {
            return '966' . $phone;
        }

        // إذا كان الرقم 10 أرقام ويبدأ بـ 0
        if (strlen($phone) === 10 && str_starts_with($phone, '0')) {
            return '966' . substr($phone, 1);
        }

        // الافتراضي
        return '966500000000';
    }

    public function verifyTransaction(array $data): array
    {
        return $this->verifyPayment($data);
    }

    public function verifyPayment(array $data): array
    {
        try {
            $orderId = $data['order_id'] ?? null;
            $paymentId = $data['payment_id'] ?? null;

            if (!$orderId && !$paymentId) {
                throw new \Exception('Order ID or Payment ID is required');
            }

            $authToken = $this->fetchAuthToken();

            // استخدام order_reference_id للبحث
            if ($orderId) {
                $response = $this->makeRequest(
                    'GET',
                    "/orders?order_reference_id={$orderId}",
                    [],
                    ['Authorization' => "Bearer {$authToken}"]
                );
            } else {
                $response = $this->makeRequest(
                    'GET',
                    "/orders/{$paymentId}",
                    [],
                    ['Authorization' => "Bearer {$authToken}"]
                );
            }

            if (!$response['success']) {
                throw new \Exception($response['error'] ?? 'Failed to verify Tamara payment');
            }

            $orderData = $response['data'];

            // إذا كان الرد قائمة، خذ أول عنصر
            if (isset($orderData[0])) {
                $orderData = $orderData[0];
            }

            $status = $this->mapPaymentStatus($orderData['status'] ?? '');

            return [
                'success' => true,
                'gateway' => 'tamara',
                'order_id' => $orderData['order_reference_id'] ?? $orderId,
                'payment_id' => $orderData['order_id'] ?? $paymentId,
                'status' => $status,
                'amount' => $orderData['total_amount']['amount'] ?? 0,
                'currency' => $orderData['total_amount']['currency'] ?? $this->currency,
                'is_paid' => in_array($status, ['authorised', 'captured', 'approved', 'new']),
                'order_details' => $orderData,
            ];
        } catch (\Exception $e) {
            Log::channel('payment')->error('Tamara Payment Verification Failed', [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'gateway' => 'tamara',
                'error' => $e->getMessage(),
                'error_code' => 'TAMARA_VERIFY_FAILED',
            ];
        }
    }

    public function refund(string $transactionId, float $amount, string $reason = ''): array
    {
        try {
            $authToken = $this->fetchAuthToken();

            $payload = [
                'order_id' => $transactionId,
                'refund_amount' => [
                    'amount' => (float) $amount,
                    'currency' => $this->currency,
                ],
                'comment' => $reason ?: 'Customer request',
                'refund_reason' => 'customer_request',
            ];

            Log::channel('payment')->debug('Tamara refund payload', [
                'transaction_id' => $transactionId,
                'payload' => $payload,
            ]);

            $response = $this->makeRequest(
                'POST',
                '/refunds',
                $payload,
                ['Authorization' => "Bearer {$authToken}"]
            );

            if (!$response['success']) {
                throw new \Exception($response['error'] ?? 'Failed to process Tamara refund');
            }

            return [
                'success' => true,
                'gateway' => 'tamara',
                'refund_id' => $response['data']['refund_id'] ?? null,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'refund_status' => 'pending',
                'raw_response' => $response['data'],
            ];
        } catch (\Exception $e) {
            Log::channel('payment')->error('Tamara Refund Failed', [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'gateway' => 'tamara',
                'error' => $e->getMessage(),
                'error_code' => 'TAMARA_REFUND_FAILED',
            ];
        }
    }

    public function refundPayment(string $transactionId, float $amount, string $reason = ''): array
    {
        return $this->refund($transactionId, $amount, $reason);
    }

    public function getTransactionStatus(string $transactionId): array
    {
        return $this->verifyPayment(['payment_id' => $transactionId]);
    }

    public function checkPaymentStatus(string $transactionId): array
    {
        return $this->getTransactionStatus($transactionId);
    }

    public function isWebhookValid(array $data): bool
    {
        // Tamara عادةً ترسل توقيع في header
        // يمكنك التحقق من وجود token في البيانات أو header
        $webhookToken = $this->config['webhook_token'] ?? config('services.tamara.webhook_token', '');
        $token = $data['token'] ?? $data['webhook_token'] ?? '';

        if (!$webhookToken || !$token) {
            Log::channel('payment')->warning('Missing Tamara webhook token', [
                'has_webhook_token' => !empty($webhookToken),
                'has_token' => !empty($token),
            ]);
            return false;
        }

        return hash_equals($webhookToken, $token);
    }

    public function handleWebhook(array $data): array
    {
        try {
            $eventType = $data['event_type'] ?? $data['event'] ?? '';
            $orderId = $data['order_reference_id'] ?? $data['order_id'] ?? null;
            $paymentId = $data['order_id'] ?? $data['payment_id'] ?? null;

            $result = [
                'success' => true,
                'gateway' => 'tamara',
                'event_type' => $eventType,
                'order_id' => $orderId,
                'payment_id' => $paymentId,
                'handled' => false,
                'status' => 'unknown',
            ];

            switch ($eventType) {
                case 'order_approved':
                case 'payment_authorised':
                    $result['status'] = 'authorised';
                    $result['handled'] = true;
                    break;

                case 'order_captured':
                case 'payment_captured':
                    $result['status'] = 'captured';
                    $result['handled'] = true;
                    break;

                case 'order_declined':
                case 'payment_declined':
                    $result['status'] = 'declined';
                    $result['handled'] = true;
                    break;

                case 'order_cancelled':
                case 'payment_cancelled':
                    $result['status'] = 'cancelled';
                    $result['handled'] = true;
                    break;

                case 'order_refunded':
                case 'payment_refunded':
                    $result['status'] = 'refunded';
                    $result['handled'] = true;
                    break;

                case 'order_expired':
                    $result['status'] = 'expired';
                    $result['handled'] = true;
                    break;

                case 'order_new':
                    $result['status'] = 'new';
                    $result['handled'] = true;
                    break;

                default:
                    $result['status'] = 'unknown';
                    Log::channel('payment')->warning('Unknown Tamara webhook event', [
                        'event_type' => $eventType,
                        'data' => $data,
                    ]);
            }

            Log::channel('payment')->info('Tamara webhook processed', $result);

            return $result;
        } catch (\Exception $e) {
            Log::channel('payment')->error('Tamara Webhook Processing Failed', [
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'gateway' => 'tamara',
                'error' => $e->getMessage(),
                'error_code' => 'TAMARA_WEBHOOK_FAILED',
            ];
        }
    }

    private function mapPaymentStatus(string $status): string
    {
        $statusMap = [
            'approved' => 'approved',
            'authorised' => 'authorised',
            'captured' => 'captured',
            'declined' => 'declined',
            'cancelled' => 'cancelled',
            'expired' => 'expired',
            'refunded' => 'refunded',
            'partially_refunded' => 'partially_refunded',
            'new' => 'new',
            'pending' => 'pending',
        ];

        return $statusMap[strtolower($status)] ?? 'unknown';
    }
}

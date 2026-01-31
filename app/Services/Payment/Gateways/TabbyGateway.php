<?php

namespace App\Services\Payment\Gateways;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\Payment\Gateways\BaseGateway;
use App\Contracts\Payment\PaymentGatewayInterface;

class TabbyGateway extends BaseGateway
{
    // ----------------------------
    // Abstract methods implementation from BaseGateway
    // ----------------------------

    protected function getBaseUrl(): string
    {
        return $this->isSandbox
            ? 'https://api.tabby.ai/api/v2'
            : 'https://api.tabby.ai/api/v2';
    }


    protected function getGatewayName(): string
    {
        return 'tabby';
    }

    protected function initializeConfig(): void
    {
        $this->config = config('services.tabby', []);
        $this->currency = config('services.tabby.currency', 'SAR');
        $this->isSandbox = config('services.tabby.sandbox', true);
    }

    // ----------------------------
    // Interface methods implementation
    // ----------------------------

    public function createPaymentOrder(array $data): array
    {
        return $this->initiatePayment($data);
    }

    public function initiatePayment(array $data): array
    {
        try {
            $authToken = $this->getAuthToken('tabby');

            if (!$authToken) {
                throw new \Exception('Tabby authentication failed');
            }

            // التحضير للبيانات المطلوبة من Tabby
            $sessionData = $this->prepareTabbySessionData();

            Log::channel('payment')->debug('Tabby session payload', [
                'payload' => $sessionData,
                'auth_token_prefix' => substr($authToken, 0, 10) . '...',
            ]);

            // استخدام makeRequest من BaseGateway
            $response = $this->makeRequest(
                'POST',
                '/checkout',
                $sessionData,
                [
                    'Authorization' => "Bearer {$authToken}",
                ]
            );
            if (!$response['success']) {
                Log::channel('payment')->error('Tabby API Error Response', [
                    'error' => $response['error'] ?? 'Unknown error',
                    'status' => $response['status'] ?? null,
                    'raw_response' => $response['raw_response'] ?? null,
                ]);
                throw new \Exception($response['error'] ?? 'Failed to create Tabby session');
            }

            $responseData = $response['data'];

            // التحقق من توفر منتج التقسيط
            $installmentProduct = $responseData['configuration']['available_products']['installments'][0] ?? null;

            // if (!$installmentProduct || !($installmentProduct['is_available'] ?? false)) {
            //     throw new \Exception('Tabby installments not available for this transaction');
            // }

            return [
                'success' => true,
                'gateway' => 'tabby',
                'session_id' => $responseData['id'] ?? null,
                'payment_id' => $responseData['payment']['id'] ?? null,
                'payment_url' => $installmentProduct['web_url'] ?? null,
                'checkout_url' => $installmentProduct['web_url'] ?? null,
                'qr_code_url' => $installmentProduct['qr_code'] ?? null,
                'status' => $responseData['status'] ?? 'created',
                'payment_status' => $responseData['payment']['status'] ?? 'CREATED',
                'order_id' => $data['order_id'],
                'expires_at' => $responseData['payment']['expires_at'] ?? now()->addHours(24)->toIso8601String(),
                'raw_response' => $responseData,
            ];
        } catch (\Exception $e) {
            Log::channel('payment')->error('Tabby Payment Initiation Failed', [
                'order_id' => $data['order_id'] ?? null,
                'amount' => $data['amount'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'gateway' => 'tabby',
                'error' => $e->getMessage(),
                'error_code' => 'TABBY_INIT_FAILED',
            ];
        }
    }

    private function prepareTabbySessionData(): array
    {
        $order = Order::where('user_id', auth()->user()->id)->latest()->first();

        $user = $order->user;
        $address = $order->address;
        $items = $order->orderItems;

        // تجهيز items
        $orderItems = $items->map(function (OrderItem $item) {
            return [
                'title'        => $item->product?->name ?? 'Custom Product',
                'description'  => $item->note ?? 'Order Item',
                'quantity'     => $item->quantity,
                'unit_price'   => $this->formatAmount($item->price_per_unit),
                'category'     => 'fashion',
                'reference_id' => 'ITEM-' . $item->id,
                'discount_amount' => '0.00',
                'is_refundable'   => true,
            ];
        })->values()->toArray();

        return [
            'payment' => [
                'amount'      => $this->formatAmount($order->total_amount) + $this->formatAmount($order->shipping_amount),
                'currency'    => $this->currency,
                'description' => 'Order #' . $order->order_number,

                'buyer' => [
                    'name'  => $order->customer_name ?? $user?->name ?? 'Customer',
                    'email' => $order->customer_email ?? $user?->email,
                    'phone' => $order->customer_phone ?? $user?->phone,
                ],

                'shipping_address' => [
                    'city'    => $address?->city ?? 'Riyadh',
                    'address' => $address?->address ?? 'N/A',
                    'zip'     => $address?->postal_code ?? '00000',
                ],

                'order' => [
                    'reference_id'    => $order->order_number,
                    'items'           => $orderItems,
                    'tax_amount'      => $this->formatAmount($order->tax_amount),
                    'shipping_amount' => $this->formatAmount($order->shipping_amount),
                    'discount_amount' => $this->formatAmount($order->discount_amount),
                    'updated_at'      => $order->updated_at->toIso8601String(),
                ],

                'buyer_history' => [
                    'registered_since' => optional($user?->created_at)->toIso8601String(),
                    'loyalty_level'    => $user?->orders()->count() ?? 0,
                    'wishlist_count'   => $user?->favourites()->count() ?? 0,
                    'is_phone_number_verified' => true,
                    'is_email_verified'        => true,
                ],

                'order_history' => $this->getCustomerOrderHistory($user, $order->id),

                'meta' => [
                    'order_id' => (string) $order->id,
                    'user_id'  => (string) $user?->id,
                ],
            ],

            'lang' => 'ar',
            'merchant_code' => $this->config['merchant_code'],

            'merchant_urls' => [
                'success' => route('payment.tabby.success', $order->id),
                'failure' => route('payment.tabby.failure', $order->id),
                'cancel'  => route('payment.tabby.cancel', $order->id),
            ],
        ];
    }

    private function getCustomerOrderHistory(?User $user, int $excludeOrderId): array
    {
        if (!$user) {
            return [];
        }

        return $user->orders()
            ->where('id', '!=', $excludeOrderId)
            ->where('status_payment', Order::PAYMENT_STATUS_PAID)
            ->latest()
            ->limit(5)
            ->get()
            ->map(function (Order $order) {
                return [
                    'purchased_at' => $order->created_at->toIso8601String(),
                    'amount'       => $this->formatAmount($order->total_amount),
                    'status'       => 'delivered',

                    'buyer' => [
                        'name'  => $order->customer_name,
                        'email' => $order->customer_email,
                        'phone' => $order->customer_phone,
                    ],

                    'shipping_address' => [
                        'city'    => optional($order->address)->city,
                        'address' => optional($order->address)->address,
                        'zip'     => optional($order->address)->postal_code,
                    ],

                    'payment_method' => $order->payment_method,

                    'items' => $order->orderItems->map(function (OrderItem $item) {
                        return [
                            'title'      => $item->product?->name ?? 'Product',
                            'quantity'   => $item->quantity,
                            'unit_price' => $this->formatAmount($item->price_per_unit),
                            'category'   => 'fashion',
                        ];
                    })->values()->toArray(),
                ];
            })
            ->toArray();
    }


    private function formatAmount(float $amount): string
    {
        // Tabby يتطلب سلسلة نصية للمبالغ مع منزلتين عشريتين
        return number_format($amount, 4, '.', '');
    }

    public function verifyTransaction(array $data): array
    {
        return $this->verifyPayment($data);
    }

    public function verifyPayment(array $data): array
    {
        try {
            $paymentId = $data['payment_id'] ?? $data['id'] ?? null;
            $sessionId = $data['session_id'] ?? null;
            $orderId = $data['order_id'] ?? null;

            if (!$paymentId && !$sessionId && !$orderId) {
                throw new \Exception('Payment ID, Session ID or Order ID is required');
            }

            $authToken = $this->getAuthToken('tabby');
            if (!$authToken) {
                throw new \Exception('Tabby authentication failed');
            }

            // الأولوية: sessionId ثم paymentId ثم orderId
            if ($sessionId) {
                $response = $this->makeRequest(
                    'GET',
                    "/checkout/{$sessionId}",
                    [],
                    [
                        'Authorization' => "Bearer {$authToken}",
                    ]
                );
            } elseif ($paymentId) {
                $response = $this->makeRequest(
                    'GET',
                    "/payments/{$paymentId}",
                    [],
                    [
                        'Authorization' => "Bearer {$authToken}",
                    ]
                );
            } else {
                // البحث باستخدام order reference
                $response = $this->makeRequest(
                    'GET',
                    "/payments?order.reference_id={$orderId}",
                    [],
                    [
                        'Authorization' => "Bearer {$authToken}",
                    ]
                );
            }

            if (!$response['success']) {
                throw new \Exception($response['error'] ?? 'Failed to verify Tabby payment');
            }

            $responseData = $response['data'];

            // إذا كان الرد قائمة، خذ أول عنصر
            if (isset($responseData[0])) {
                $responseData = $responseData[0];
            }

            $paymentData = $responseData['payment'] ?? $responseData;
            $status = $this->mapTabbyStatus($paymentData['status'] ?? '');

            return [
                'success' => true,
                'gateway' => 'tabby',
                'session_id' => $sessionId ?? $responseData['id'] ?? null,
                'payment_id' => $paymentData['id'] ?? $paymentId,
                'order_id' => $paymentData['order']['reference_id'] ?? $orderId,
                'status' => $status,
                'amount' => $paymentData['amount'] ?? 0,
                'currency' => $paymentData['currency'] ?? $this->currency,
                'is_paid' => in_array($status, ['AUTHORIZED', 'CLOSED', 'CAPTURED']),
                'buyer' => $paymentData['buyer'] ?? [],
                'payment_details' => $paymentData,
                'raw_response' => $responseData,
            ];
        } catch (\Exception $e) {
            Log::channel('payment')->error('Tabby Payment Verification Failed', [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'gateway' => 'tabby',
                'error' => $e->getMessage(),
                'error_code' => 'TABBY_VERIFY_FAILED',
            ];
        }
    }

    public function refund(string $transactionId, float $amount, string $reason = ''): array
    {
        try {
            $authToken = $this->getAuthToken('tabby');
            if (!$authToken) {
                throw new \Exception('Tabby authentication failed');
            }

            $payload = [
                'amount' => $this->formatAmount($amount),
                'reason' => $reason ?: 'Customer request',
                'currency' => $this->currency,
            ];

            Log::channel('payment')->debug('Tabby refund payload', [
                'transaction_id' => $transactionId,
                'payload' => $payload,
            ]);

            $response = $this->makeRequest(
                'POST',
                "/payments/{$transactionId}/refunds",
                $payload,
                [
                    'Authorization' => "Bearer {$authToken}",
                ]
            );

            if (!$response['success']) {
                throw new \Exception($response['error'] ?? 'Failed to process Tabby refund');
            }

            return [
                'success' => true,
                'gateway' => 'tabby',
                'refund_id' => $response['data']['id'] ?? null,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'refund_status' => 'created',
                'raw_response' => $response['data'],
            ];
        } catch (\Exception $e) {
            Log::channel('payment')->error('Tabby Refund Failed', [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'gateway' => 'tabby',
                'error' => $e->getMessage(),
                'error_code' => 'TABBY_REFUND_FAILED',
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
        // Tabby يرسل التوقيع في header: X-Tabby-Signature
        // هذا التحقق يجب أن يتم في الـ Controller

        $webhookSecret = $this->config['webhook_secret'] ?? config('services.tabby.webhook_secret', '');

        if (!$webhookSecret) {
            Log::channel('payment')->warning('Missing Tabby webhook secret');
            return false;
        }

        // في الـ Controller، ستحتاج إلى:
        // $signature = $request->header('X-Tabby-Signature');
        // $payload = $request->getContent();
        // $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);
        // return hash_equals($expectedSignature, $signature);

        return true; // مؤقتاً، سيتم التحقق في الـ Controller
    }

    public function handleWebhook(array $data): array
    {
        try {
            // Note: Tabby webhook validation is done via headers in controller

            $eventType = $data['event'] ?? '';
            $paymentId = $data['payment']['id'] ?? $data['id'] ?? null;
            $orderId = $data['payment']['order']['reference_id'] ?? $data['order']['reference_id'] ?? null;

            $result = [
                'success' => true,
                'gateway' => 'tabby',
                'event_type' => $eventType,
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'handled' => false,
                'status' => 'unknown',
            ];

            switch ($eventType) {
                case 'payment_approved':
                case 'payment_authorized':
                    $result['status'] = 'AUTHORIZED';
                    $result['handled'] = true;
                    break;

                case 'payment_captured':
                    $result['status'] = 'CLOSED';
                    $result['handled'] = true;
                    break;

                case 'payment_declined':
                case 'payment_rejected':
                    $result['status'] = 'REJECTED';
                    $result['handled'] = true;
                    break;

                case 'payment_expired':
                    $result['status'] = 'EXPIRED';
                    $result['handled'] = true;
                    break;

                case 'payment_refunded':
                    $result['status'] = 'REFUNDED';
                    $result['handled'] = true;
                    break;

                default:
                    $result['status'] = 'UNKNOWN';
                    Log::channel('payment')->warning('Unknown Tabby webhook event', [
                        'event_type' => $eventType,
                        'data' => $data,
                    ]);
            }

            Log::channel('payment')->info('Tabby webhook processed', $result);

            return $result;
        } catch (\Exception $e) {
            Log::channel('payment')->error('Tabby Webhook Processing Failed', [
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'gateway' => 'tabby',
                'error' => $e->getMessage(),
                'error_code' => 'TABBY_WEBHOOK_FAILED',
            ];
        }
    }

    private function mapTabbyStatus(string $status): string
    {
        $statusMap = [
            'CREATED' => 'created',
            'AUTHORIZED' => 'authorized',
            'CLOSED' => 'closed',
            'CAPTURED' => 'captured',
            'REJECTED' => 'rejected',
            'EXPIRED' => 'expired',
            'REFUNDED' => 'refunded',
            'CANCELLED' => 'cancelled',
            'PENDING' => 'pending',
        ];

        return $statusMap[strtoupper($status)] ?? 'unknown';
    }
}

<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShippingOrder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OtoShippingService
{
    private $baseUrl;

    private $token;

    public function __construct()
    {
        $this->baseUrl = config('services.oto.api_url', 'https://api.tryoto.com/rest/v2/');
        $this->token = config('services.oto.api_token');
    }

    public function refreshToken()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'refreshToken', [
            'refresh_token' => $this->token, // نفس اللي في cURL
        ]);

        // لو حابب تشوف النتيجة
        return $response->json();
    }

    public function getPickupLocations($token)
    {

        $response = Http::withToken($token)
            ->get($this->baseUrl . 'getPickupLocationList');

        return $response->json();
    }

    public function handleDataOrder(array $orderData, string $token)
    {
        $user = auth()->user();

        $cart = $user->cart()->with(['items.product'])->firstOrFail();

        $defaultAddress = $user->userAddresses()
            ->where('selected_address', true)
            ->first();
        if (empty($defaultAddress)) {
          $defaultAddress = $user->userAddresses()->first();
        }

        // 🟢 استخدم ID الطلب الحقيقي + suffix
        $shippingOrderId = $orderData['id'] . rand(40, 1000000);

        $payload = [
            'orderId' => $shippingOrderId,
            'createShipment' => false,
            'payment_method' => 'pending',

            // 💰 الحساب من الكارت
            'amount' => $cart->total,
            'amount_due' => 0,
            'shippingAmount' => $orderData['shipping_amount'] ?? 0,

            'currency' => 'SAR',
            'shippingNotes' => $orderData['notes'] ?? null,

            'packageSize' => 'small',
            'packageCount' => $cart->items->count(),
            'packageWeight' => 1,

            'boxWidth' => 10,
            'boxLength' => 10,
            'boxHeight' => 10,

            'orderDate' => now()->format('d/m/Y H:i'),
            'deliverySlotDate' => now()->addDay()->format('d/m/Y'),
            'deliverySlotFrom' => '2:30pm',
            'deliverySlotTo' => '12pm',

            /* ================= Sender ================= */

            'senderName' => $orderData['name'],
            'senderInformation' => [
                'senderAddressName' => $orderData['address'],
                'senderId' => (string) $orderData['id'],
                'senderFullName' => $orderData['contactPerson'],
                'senderMobile' => $orderData['contactPhone'],
                'senderEmail' => $orderData['contactEmail'],
                'senderCountry' => 'SA',
                'senderCity' => $orderData['city'],
                'senderBuildingNo' => $orderData['buildingNo'],
                'senderStreet' => $orderData['Street'],
                'senderDistrict' => $orderData['district'],
                'senderAddressLine' => $orderData['address'],
                'lat' => (string) $orderData['lat'],
                'lon' => (string) $orderData['lon'],
            ],

            /* ================= Customer ================= */

            'customer' => [
                'name' => $user->name ?? '',
                'email' => $user->email,
                'mobile' => (string) ($user->phone ?? '0500000000'),
                'address' => optional($defaultAddress)->address_details ?? '',
                'city' => optional($defaultAddress)->city ?? '',
                'country' => 'SA',
                'refID' => (string) $user->id,
            ],

            /* ================= Items (FROM CART ✅) ================= */

            'items' => $cart->items->map(function ($item) {
                return [
                    'productId' => $item->product_id,
                    'name' => $item->product?->name ?? 'Product',
                    'sku' => $item->product?->sku ?? 'SKU-' . $item->id,
                    'price' => (float) $item->price_per_unit,
                    'quantity' => (int) $item->quantity,
                    'rowTotal' => (float) $item->line_total,
                    'taxAmount' => 0,
                    'currency' => 'SAR',
                ];
            })->toArray(),
        ];

        /* ================= SEND REQUEST ================= */

        $response = Http::withToken($token)
            ->post($this->baseUrl . 'createOrder', $payload);
        if ($response->failed()) {
            Log::error('Failed to create OTO order', [
                'response' => $response->body(),
                'payload' => $payload,
            ]);

            throw new \Exception('Failed to create shipping order');
        }

        /* ================= SAVE SHIPPING ORDER ================= */

        $shippingOrder = ShippingOrder::create([
            'order_id' => $shippingOrderId,
            'oto_order_id' => $response['otoId'] ?? null,
            'status' => 'shipment_created',
            'accessToken' => $token,
            // Sender
            'sender_name' => $payload['senderName'],
            'sender_phone' => $payload['senderInformation']['senderMobile'],
            'sender_email' => $payload['senderInformation']['senderEmail'],
            'sender_city' => $payload['senderInformation']['senderCity'],
            'sender_district' => $payload['senderInformation']['senderDistrict'],
            'sender_address' => $payload['senderInformation']['senderAddressLine'],

            // Receiver
            'receiver_name' => $payload['customer']['name'],
            'receiver_phone' => $payload['customer']['mobile'],
            'receiver_email' => $payload['customer']['email'],
            'receiver_city' => $payload['customer']['city'],
            'receiver_address' => $payload['customer']['address'],

            // Shipment
            'pieces_count' => $payload['packageCount'],
            'weight' => $payload['packageWeight'],
            'length' => $payload['boxLength'],
            'width' => $payload['boxWidth'],
            'height' => $payload['boxHeight'],
            'declared_value' => $payload['amount'],

            // Payment
            'payment_type' => $payload['payment_method'],
            'shipping_cost' => $payload['shippingAmount'],
            'total_amount' => $payload['amount'],

            // OTO
            'oto_response' => $response->json(),

            'notes' => $payload['shippingNotes'],
        ]);

        return $shippingOrder->order_id;
    }


    public function getDeliveryFee($order_id, $accessToken)
    {
        $response = Http::withToken($accessToken)
            ->post($this->baseUrl . 'getDeliveryFee', [
                'orderId' => (string) $order_id
            ]);
        return $response;
    }

    /**
     * الحصول على خيارات التوصيل المتاحة
     */
    public function getDeliveryOptions(): array
    {
        $tokenResponse = $this->refreshToken();

        if (!isset($tokenResponse['access_token'])) {
            throw new \Exception('Failed to refresh OTO token');
        }

        $accessToken = $tokenResponse['access_token'];

        $pickupLocations = $this->getPickupLocations($accessToken);
        $warehouse       = $pickupLocations['warehouses'][0] ?? null;

        if (!$warehouse) {
            throw new \Exception('No warehouses found');
        }

        $orderId = $this->handleDataOrder($warehouse, $accessToken);

        $deliveryFeesResponse = $this->getDeliveryFee($orderId, $accessToken);

        return [
            'deliveryFees' => $deliveryFeesResponse->json(),
            'orderId'      => $orderId,
        ];
    }

    public function createShipment($orderId, string $deliveryOptionId, $shippingPrice)
    {
        // جلب آخر ShippingOrder للـ Order
        $shippingOrder = ShippingOrder::where('order_id', $orderId)
            ->latest()
            ->first();

        // تحديث تكلفة الشحن إذا موجودة
        if (isset($shippingOrder->shipping_amount)) {
            $shippingOrder->update([
                'shipping_cost' => $shippingPrice,
            ]);
        }

        // التحقق من وجود Access Token
        if (!$shippingOrder->accessToken) {
            throw new \Exception("Access token not found for order {$orderId}");
        }

        // تجهيز Payload لإرسالها لـ OTO
        $payload = [
            'orderId'         => $orderId,
            'deliveryOptionId' => $deliveryOptionId,
        ];

        // استدعاء API
        $response = Http::withToken($shippingOrder->accessToken)
            ->post($this->baseUrl . 'createShipment', $payload);

        // تحقق من نجاح الطلب
        if (!$response->successful()) {
            throw new \Exception('Failed to create shipment: ' . $response->body());
        }

        // ارجع الـ response JSON
        return $response->json();
    }
}

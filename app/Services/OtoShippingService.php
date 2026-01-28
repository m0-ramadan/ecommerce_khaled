<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Shipment;
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
        ])->post($this->baseUrl.'refreshToken', [
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
    $payload = [
        "orderId" => (string) $orderData['id'],
        "createShipment" => false,
        "payment_method" => "paid",
        "amount" => 99,
        "amount_due" => 0,
      //  "brandId" => $orderData['brand_id'] ?? null,
       // "customsValue" => "12",
       // "customsCurrency" => "USD",
        "shippingAmount" => $orderData['shipping_amount'] ?? 0,
      //  "subtotal" => $orderData['subtotal'],
        "currency" => "SAR",
        "shippingNotes" => $orderData['notes'] ?? null,
        "packageSize" => "small",
        "packageCount" => 1,
        "packageWeight" => 1,
        "boxWidth" => 10,
        "boxLength" => 10,
        "boxHeight" => 10,
        "orderDate" => now()->format('d/m/Y H:i'),
        "deliverySlotDate" => now()->addDay()->format('d/m/Y'),
        "deliverySlotFrom" => "2:30pm",
        "deliverySlotTo" => "12pm",

        // Sender (Warehouse)
        "senderName" => $orderData['name'],
        "senderInformation" => [
            "senderAddressName" => $orderData['address'],
            "senderId" => (string) $orderData['id'],
            "senderFullName" => $orderData['contactPerson'],
            "senderMobile" => $orderData['contactPhone'],
            "senderEmail" => $orderData['contactEmail'],
            "senderCountry" => "SA",
            "senderCity" => $orderData['city'],
            "senderBuildingNo" => $orderData['buildingNo'],
          //  "senderPostcode" => $orderData['postcode'],
            "senderStreet" => $orderData['Street'],
            "senderDistrict" => $orderData['district'],
          //  "senderSecondaryAddressNumber" => $orderData['secondary_no'],
            "senderAddressLine" => $orderData['address'],
          //  "senderShortAddressCode" => $orderData['short_code'],
            "lat" => (string) $orderData['lat'],
            "lon" => (string) $orderData['lon'],
        ],

        // Customer
        "customer" => [
            "name" => $user->name,
            "email" => $user->email??null,
            "mobile" => $user->phone??null,
            "address" => $user->addresses->first()->address_details ?? '',
          //  "district" => $orderData['customer']['district'],
            "city" => $user->addresses->first()->city ?? '',
           // "buildingNo" => $orderData['customer']['building_no'],
          //  "street" => $orderData['customer']['street'],
          //  "shortAddressCode" => $orderData['customer']['short_code'],
            "country" => "SA",
           // "postcode" => $orderData['customer']['postcode'],
           // "lat" => (string) $orderData['customer']['lat'],
           // "lon" => (string) $orderData['customer']['lon'],
            "refID" => $user->id,
        ],

        // Items
        "items" => collect($user->cart->items)->map(function ($item) {
            return [
                "productId" => $item['id'],
                "name" => $item['name'],
                "price" => $item['price'],
                "rowTotal" => $item['price'] * $item['qty'],
                "taxAmount" => $item['tax'] ?? 0,
                "quantity" => $item['qty'],
                "sku" => $item['sku'],
                "currency" => "SAR",
            ];
        })->toArray(),
    ];

    $response = Http::withToken($token)
        ->post($this->baseUrl . 'createOrder', $payload);
dd($response);
    return $response->json();
}
    /**
     * الحصول على خيارات التوصيل المتاحة
     */
    public function getDeliveryOptions()
    {
        $tokenResponse = $this->refreshToken();

        if (! isset($tokenResponse['access_token'])) {
            throw new \Exception('Failed to refresh OTO token');
        }

        $accessToken = $tokenResponse['access_token'];

        $pickupLocations = $this->getPickupLocations($accessToken);
        $warehouses = $pickupLocations['warehouses'][0] ?? [];
        $this->handleDataOrder($warehouses, $accessToken);

        //return $warehouses['deliveryOptions'] ?? [];

    }

    /**
     * تحديث حالة الشحنة
     */
    public function updateShipmentStatus(Shipment $shipment): bool
    {
        if (! $shipment->tracking_number) {
            return false;
        }

        $response = Http::withToken($this->token)
            ->get($this->baseUrl.'trackShipment', [
                'trackingNumber' => $shipment->tracking_number,
            ]);

        if ($response->successful()) {
            $trackingData = $response->json();
            $this->updateTrackingHistory($shipment, $trackingData);

            return true;
        }

        return false;
    }

    /**
     * إعداد معلومات المرسل
     */
    private function prepareSenderInfo(Order $order): array
    {
        return [
            'name' => config('app.name'),
            'address' => config('services.oto.sender_address'),
            'city' => config('services.oto.sender_city'),
            'phone' => config('services.oto.sender_phone'),
            'email' => config('services.oto.sender_email'),
        ];
    }

        /**
     * التحقق من تكلفة التوصيل
     */
    public function checkDeliveryFee(array $params): array
    {
        $response = Http::withToken($this->token)
            ->post($this->baseUrl.'checkOTODeliveryFee', $params);

        return $response->json();
    }

        /**
     * إنشاء شحنة جديدة
     */
    public function createShipment(Order $order, array $data): ?Shipment
    {
        try {
            $response = Http::withToken($this->token)
                ->post($this->baseUrl.'createShipment', [
                    'orderId' => $order->order_number,
                    'deliveryOptionId' => $data['delivery_option_id'],
                ]);

            if ($response->successful()) {
                $shipmentData = $response->json();

                $shipment = Shipment::create([
                    'order_id' => $order->id,
                    'external_shipment_id' => $shipmentData['shipmentId'] ?? null,
                    'tracking_number' => $shipmentData['trackingNumber'] ?? null,
                    'status' => Shipment::STATUS_PENDING,
                    'delivery_option_id' => $data['delivery_option_id'],
                    'estimated_delivery_date' => $shipmentData['estimatedDeliveryDate'] ?? null,
                    'shipping_cost' => $shipmentData['shippingCost'] ?? 0,
                    'tracking_url' => $shipmentData['trackingUrl'] ?? null,
                    'shipment_details' => $shipmentData,
                    'sender_info' => $this->prepareSenderInfo($order),
                    'recipient_info' => $this->prepareRecipientInfo($order),
                    'created_by' => auth()->id(),
                ]);

                // تحديث حالة الطلب
                $order->update(['status' => 'processing']);

                return $shipment;
            }

            Log::error('Failed to create shipment', [
                'order_id' => $order->id,
                'response' => $response->body(),
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Exception in createShipment', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * إعداد معلومات المستلم
     */
    private function prepareRecipientInfo(Order $order): array
    {
        $address = $order->address;

        return [
            'name' => $order->customer_name,
            'phone' => $order->customer_phone,
            'email' => $order->customer_email,
            'address' => $address->address_details ?? '',
            'city' => $address->city ?? '',
            'area' => $address->area ?? '',
            'building' => $address->building ?? '',
            'apartment' => $address->apartment_number ?? '',
        ];
    }

    /**
     * تحديث سجل التتبع
     */
    private function updateTrackingHistory(Shipment $shipment, array $trackingData): void
    {
        foreach ($trackingData['history'] ?? [] as $event) {
            $shipment->trackingHistory()->create([
                'status' => $event['status'],
                'description' => $event['description'],
                'location' => $event['location'],
                'event_date' => $event['date'],
                'event_time' => $event['time'],
            ]);
        }

        // تحديث حالة الشحنة الرئيسية
        if (isset($trackingData['current_status'])) {
            $shipment->update([
                'status' => $this->mapStatus($trackingData['current_status']),
                'actual_delivery_date' => $trackingData['delivered_at'] ?? null,
            ]);
        }
    }

    /**
     * تحويل حالة OTO لحالتنا
     */
    private function mapStatus(string $otoStatus): string
    {
        $statusMap = [
            'pending' => Shipment::STATUS_PENDING,
            'picked_up' => Shipment::STATUS_PICKED_UP,
            'in_transit' => Shipment::STATUS_IN_TRANSIT,
            'out_for_delivery' => Shipment::STATUS_OUT_FOR_DELIVERY,
            'delivered' => Shipment::STATUS_DELIVERED,
            'failed' => Shipment::STATUS_FAILED,
            'cancelled' => Shipment::STATUS_CANCELLED,
        ];

        return $statusMap[$otoStatus] ?? Shipment::STATUS_PENDING;
    }
}

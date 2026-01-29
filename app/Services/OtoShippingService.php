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
        ])->post($this->baseUrl.'refreshToken', [
            'refresh_token' => $this->token, // نفس اللي في cURL
        ]);

        // لو حابب تشوف النتيجة
        return $response->json();
    }

    public function getPickupLocations($token)
    {

        $response = Http::withToken($token)
            ->get($this->baseUrl.'getPickupLocationList');

        return $response->json();
    }

    public function handleDataOrder(array $orderData, string $token)
    {
        $user = auth()->user();
        $defaultAddress = $user->userAddresses->where('selected_address', true)->first();
        $payload = [
            'orderId' => (string) $orderData['id'].rand(600, 100000),
            'createShipment' => false,
            'payment_method' => 'pendding',
            'amount' => 99,
            'amount_due' => 0,
            //  "brandId" => $orderData['brand_id'] ?? null,
            // "customsValue" => "12",
            // "customsCurrency" => "USD",
            'shippingAmount' => $orderData['shipping_amount'] ?? 0,
            //  "subtotal" => $orderData['subtotal'],
            'currency' => 'SAR',
            'shippingNotes' => $orderData['notes'] ?? null,
            'packageSize' => 'small',
            'packageCount' => 1,
            'packageWeight' => 1,
            'boxWidth' => 10,
            'boxLength' => 10,
            'boxHeight' => 10,
            'orderDate' => now()->format('d/m/Y H:i'),
            'deliverySlotDate' => now()->addDay()->format('d/m/Y'),
            'deliverySlotFrom' => '2:30pm',
            'deliverySlotTo' => '12pm',

            // Sender (Warehouse)
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
                //  "senderPostcode" => $orderData['postcode'],
                'senderStreet' => $orderData['Street'],
                'senderDistrict' => $orderData['district'],
                //  "senderSecondaryAddressNumber" => $orderData['secondary_no'],
                'senderAddressLine' => $orderData['address'],
                //  "senderShortAddressCode" => $orderData['short_code'],
                'lat' => (string) $orderData['lat'],
                'lon' => (string) $orderData['lon'],
            ],

            // Customer
            'customer' => [
                'name' => $user->name ?? '',
                'email' => $user->email ?? null,
                // "mobile" =>(string) $user->phone??'546607389',
                'mobile' => (string) '546607389',
                'address' => optional($defaultAddress)->address_details ?? '',
                //  "district" => $orderData['customer']['district'],
                'city' => optional($defaultAddress)->city ?? '',
                // "buildingNo" => $orderData['customer']['building_no'],
                //  "street" => $orderData['customer']['street'],
                //  "shortAddressCode" => $orderData['customer']['short_code'],
                'country' => 'SA',
                // "postcode" => $orderData['customer']['postcode'],
                // "lat" => (string) $orderData['customer']['lat'],
                // "lon" => (string) $orderData['customer']['lon'],
                'refID' => $user->id,
            ],

            // Items
            'items' => collect($user->cart->items)->map(function ($item) {
                return [
                    'productId' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'rowTotal' => $item['price'] * $item['qty'],
                    'taxAmount' => $item['tax'] ?? 0,
                    'quantity' => $item['qty'] ?? 1,
                    'sku' => $item['sku'],
                    'currency' => 'SAR',
                ];
            })->toArray(),
        ];

        $response = Http::withToken($token)
            ->post($this->baseUrl.'createOrder', $payload);
        if ($response->failed()) {
            Log::error('Failed to create OTO order', [
                'response' => $response->body(),
                'payload' => $payload,
            ]);
        }

        $shippingOrder = ShippingOrder::create([

            // Order
            'order_id' => $payload['orderId'],
            'oto_order_id' => $response['otoId'],
            'status' => 'shipment_created',

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
            'receiver_postal_code' => null,
            'short_address_code' => null,

            // Shipment details
            'pieces_count' => $payload['packageCount'],
            'weight' => $payload['packageWeight'],
            'length' => $payload['boxLength'],
            'width' => $payload['boxWidth'],
            'height' => $payload['boxHeight'],
            'declared_value' => $payload['amount'],
            'content_type' => 'products',
            'content_description' => 'Order Items',

            // Payment
            'payment_type' => $payload['payment_method'],
            'shipping_cost' => $payload['shippingAmount'],
            'cash_on_delivery_amount' => 0,
            'total_amount' => $payload['amount'],
       //     'who_pays' => 'sender',

            // Service
        //   'delivery_company' => 'OTO',
          //  'service_type' => 'standard',
           // 'pickup_location' => $payload['senderInformation']['senderId'],
           // 'delivery_type' => 'door_to_door',

            // OTO
            'oto_response' => $response,

            // Notes
            'notes' => $payload['shippingNotes'],
        ]);

        return $shippingOrder->order_id;
    }

    public function getDeliveryFee($order_id,$accessToken){
        $response = Http::withToken($accessToken)
            ->post($this->baseUrl.'getDeliveryFee', [
                'orderId' =>(string) $order_id
            ]);
            
        return $response;
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
        $order_id = $this->handleDataOrder($warehouses, $accessToken);

        $deliveryFees=$this->getDeliveryFee($order_id,$accessToken);

         return $deliveryFees->json();

    }



}

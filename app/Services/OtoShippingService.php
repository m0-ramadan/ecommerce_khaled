<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShippingOrder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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

    /**
     * حساب الأوزان والأبعاد الفعلية وحجم الطرد بناء على المنتجات وكميات الباتش
     */
    public function calculateCartPackageMetrics($cart): array
    {
        $totalWeight = 0.0;
        $totalPiecesCount = 0;

        foreach ($cart->items as $item) {
            $product = $item->product;
            $quantity = max(1, (int) $item->quantity);

            // استخراج كمية الباتش (Batch Multiplier) من selected_options (مثل 100 حبة، 500 حبة، 1000 حبة)
            $batchMultiplier = 1;
            if (!empty($item->selected_options)) {
                $options = is_array($item->selected_options)
                    ? $item->selected_options
                    : (json_decode($item->selected_options, true) ?: []);

                foreach ($options as $opt) {
                    $optName = $opt['option_name'] ?? '';
                    $optVal = $opt['option_value'] ?? '';
                    if (str_contains($optName, 'الكمية') || str_contains($optName, 'العدد') || str_contains($optName, 'كمية')) {
                        if (preg_match('/(\d+)\s*(حبة|حبات|حبه|قطعة|قطع|شيت|كرتون|علبة|كيس)/u', $optVal, $matches)) {
                            $batchMultiplier = max(1, (int) $matches[1]);
                        } elseif (preg_match('/^(\d+)/', trim($optVal), $matches)) {
                            $batchMultiplier = max(1, (int) $matches[1]);
                        }
                    }
                }
            }

            $effectivePieces = $quantity * $batchMultiplier;
            $totalPiecesCount += $effectivePieces;

            // تحديد وزن القطعة بالكيلوجرام بناء على الفئة ونوع المنتج
            if (!empty($product?->weight) && (float) $product->weight > 0) {
                $unitWeight = (float) $product->weight;
                if ($batchMultiplier > 1 && $unitWeight > 1.0) {
                    $itemWeight = $unitWeight * $quantity;
                } else {
                    $itemWeight = $unitWeight * $effectivePieces;
                }
            } else {
                $categorySlug = $product?->category?->slug ?? '';
                $productName = $product?->name ?? '';

                if (str_contains($productName, 'رول اب') || str_contains($productName, 'استاند') || $categorySlug === 'daaayh-o-aelam') {
                    $unitWeight = 3.5;
                    $itemWeight = $unitWeight * $quantity;
                } elseif (str_contains($productName, 'كرتون') || $categorySlug === 'kratyn-shhn') {
                    $unitWeight = 0.35;
                    $itemWeight = $unitWeight * $effectivePieces;
                } elseif (str_contains($productName, 'كوب') || str_contains($productName, 'اكواب') || $categorySlug === 'akoab') {
                    $unitWeight = 0.25;
                    $itemWeight = $unitWeight * $effectivePieces;
                } elseif (str_contains($productName, 'بوكس') || $categorySlug === 'boksat') {
                    $unitWeight = 0.15;
                    $itemWeight = $unitWeight * $effectivePieces;
                } elseif (str_contains($productName, 'كيس') || $categorySlug === 'akyas') {
                    $unitWeight = 0.04;
                    $itemWeight = $unitWeight * $effectivePieces;
                } elseif (str_contains($productName, 'ملصق') || str_contains($productName, 'ستيكر') || $categorySlug === 'mlskat') {
                    $unitWeight = 0.015;
                    $itemWeight = $unitWeight * $effectivePieces;
                } elseif (str_contains($productName, 'شماسة')) {
                    $unitWeight = 0.35;
                    $itemWeight = $unitWeight * $quantity;
                } else {
                    $unitWeight = (float) config('services.oto.default_item_weight', 0.5);
                    $itemWeight = $unitWeight * $quantity;
                }
            }

            $totalWeight += $itemWeight;
        }

        $totalWeight = max(0.5, round($totalWeight, 2));

        // حساب الأبعاد وحجم الطرد وفق معايير شركات الشحن (Volumetric & Weight Tiers)
        if ($totalWeight <= 2.0) {
            $packageSize = 'small';
            $boxLength = 20;
            $boxWidth = 15;
            $boxHeight = 10;
            $packageCount = 1;
        } elseif ($totalWeight <= 8.0) {
            $packageSize = 'medium';
            $boxLength = 35;
            $boxWidth = 25;
            $boxHeight = 20;
            $packageCount = 1;
        } elseif ($totalWeight <= 20.0) {
            $packageSize = 'large';
            $boxLength = 50;
            $boxWidth = 40;
            $boxHeight = 30;
            $packageCount = 1;
        } else {
            $packageSize = 'large';
            $packageCount = max(1, (int) ceil($totalWeight / 20));
            $boxLength = 60;
            $boxWidth = 45;
            $boxHeight = 40;
        }

        return [
            'weight' => $totalWeight,
            'packageSize' => $packageSize,
            'packageCount' => $packageCount,
            'boxWidth' => $boxWidth,
            'boxLength' => $boxLength,
            'boxHeight' => $boxHeight,
            'totalPiecesCount' => $totalPiecesCount,
        ];
    }

    public function normalizeCityName(?string $city): string
    {
        if (empty($city)) {
            return 'Riyadh';
        }

        $trimmed = trim($city);
        return $trimmed ?: 'Riyadh';
    }

    public function handleDataOrder(array $orderData, string $token, $addressId = null, $cityName = null)
    {
        $user = auth()->user();

        $cart = $user->cart()->with(['items.product.category'])->firstOrFail();

        if ($cart->items->isEmpty()) {
            throw new \InvalidArgumentException('السلة فارغة');
        }

        // 1. تحديد العنوان والموقع
        $address = null;
        if (!empty($addressId)) {
            $address = $user->userAddresses()->find($addressId);
        }
        if (!$address) {
            $address = $user->userAddresses()
                ->where('selected_address', true)
                ->first() ?: $user->userAddresses()->latest()->first();
        }

        $customerCity = $this->normalizeCityName($cityName ?: optional($address)->city);
        $customerAddress = optional($address)->address_details ?: $customerCity;
        $customerDistrict = optional($address)->area ?: 'الوسطى';
        $customerPhone = (string) (optional($address)->phone ?: ($user->phone ?: '0500000000'));

        // 2. الحساب الديناميكي للوزن والأبعاد وحجم الطرد
        $metrics = $this->calculateCartPackageMetrics($cart);

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

            'packageSize' => $metrics['packageSize'],
            'packageCount' => $metrics['packageCount'],
            'packageWeight' => $metrics['weight'],

            'boxWidth' => $metrics['boxWidth'],
            'boxLength' => $metrics['boxLength'],
            'boxHeight' => $metrics['boxHeight'],

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
                'name' => $user->name ?: 'عميل متجر تالا',
                'email' => $user->email ?: 'customer@talaaljazeera.com',
                'mobile' => $customerPhone,
                'address' => $customerAddress,
                'district' => $customerDistrict,
                'city' => $customerCity,
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
     * الحصول على خيارات التوصيل المتاحة مع تحديد العنوان والمدينة
     */
    public function getDeliveryOptions($addressId = null, $cityName = null): array
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

        $orderId = $this->handleDataOrder($warehouse, $accessToken, $addressId, $cityName);

        $deliveryFeesResponse = $this->getDeliveryFee($orderId, $accessToken);

        if (!$deliveryFeesResponse->successful()) {
            throw new \RuntimeException('OTO delivery fee request failed: ' . $deliveryFeesResponse->body());
        }

        $deliveryFees = $deliveryFeesResponse->json();
        $shippingOrder = ShippingOrder::where('order_id', $orderId)->latest()->firstOrFail();
        $otoResponse = is_array($shippingOrder->oto_response) ? $shippingOrder->oto_response : [];
        $shippingOrder->update([
            'oto_response' => array_merge($otoResponse, ['delivery_fees' => $deliveryFees]),
        ]);

        return [
            'deliveryFees' => $deliveryFees,
            'orderId'      => $orderId,
        ];
    }

    /**
     * Resolve the selected option from the server-side quote saved for this OTO order.
     */
    public function resolveDeliveryOption(string $orderId, string $deliveryOptionId): array
    {
        $shippingOrder = ShippingOrder::where('order_id', $orderId)->latest()->first();
        $fees = data_get($shippingOrder?->oto_response, 'delivery_fees');

        if (!is_array($fees)) {
            throw new \InvalidArgumentException('عرض الشحن غير موجود أو منتهي. يرجى إعادة تحميل شركات الشحن.');
        }

        $option = $this->findDeliveryOption($fees, $deliveryOptionId);
        if (!$option) {
            throw new \InvalidArgumentException('شركة الشحن المختارة ليست ضمن العرض الحالي.');
        }

        $price = $this->firstNumericValue($option, [
            'price',
            'amount',
            'cost',
            'fee',
            'deliveryFee',
            'shippingCost',
            'total',
            'totalAmount',
        ]);

        if ($price === null || $price < 0) {
            throw new \InvalidArgumentException('تعذر التحقق من تكلفة شركة الشحن المختارة.');
        }

        return [
            'id' => $deliveryOptionId,
            'price' => $price,
            'name' => $option['name'] ?? $option['deliveryOptionName'] ?? $option['companyName']
                ?? $option['carrierName'] ?? $option['company'] ?? 'شركة شحن',
            'service_type' => $option['serviceType'] ?? $option['service_type'] ?? null,
        ];
    }

    private function findDeliveryOption(array $data, string $deliveryOptionId): ?array
    {
        foreach ($data as $value) {
            if (!is_array($value)) {
                continue;
            }

            $id = $value['deliveryOptionId'] ?? $value['optionId'] ?? $value['id'] ?? null;
            if ($id !== null && (string) $id === $deliveryOptionId) {
                return $value;
            }

            $found = $this->findDeliveryOption($value, $deliveryOptionId);
            if ($found) {
                return $found;
            }
        }

        return null;
    }

    private function firstNumericValue(array $option, array $keys): ?float
    {
        foreach ($keys as $key) {
            $value = data_get($option, $key);
            if (is_numeric($value)) {
                return (float) $value;
            }
        }

        return null;
    }

    public function createShipment($orderId, string $deliveryOptionId, $shippingPrice)
    {
        // جلب آخر ShippingOrder للـ Order
        $shippingOrder = ShippingOrder::where('order_id', $orderId)
            ->latest()
            ->first();

        // تحديث تكلفة الشحن إذا موجودة
        if (!$shippingOrder) {
            throw new \InvalidArgumentException("Shipping order not found for order {$orderId}");
        }

        $selectedOption = $this->resolveDeliveryOption((string) $orderId, $deliveryOptionId);

        $updateData = [
            'shipping_cost' => max(0, (float) $shippingPrice),
            'shipping_service_id' => is_numeric($deliveryOptionId) ? (int) $deliveryOptionId : null,
        ];

        if (Schema::hasColumn('shipping_orders', 'delivery_company')) {
            $updateData['delivery_company'] = $selectedOption['name'];
        }
        if (Schema::hasColumn('shipping_orders', 'service_type')) {
            $updateData['service_type'] = $selectedOption['service_type'];
        }

        $otoResponse = is_array($shippingOrder->oto_response) ? $shippingOrder->oto_response : [];
        $updateData['oto_response'] = array_merge($otoResponse, [
            'delivery_company' => $selectedOption['name'],
            'service_type' => $selectedOption['service_type'],
        ]);

        $shippingOrder->update($updateData);

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

        $result = $response->json();
        $otoResponse = is_array($shippingOrder->oto_response) ? $shippingOrder->oto_response : [];
        $shippingOrder->update([
            'status' => 'shipment_created',
            'oto_tracking_number' => $result['trackingNumber'] ?? $result['tracking_number'] ?? $result['awb'] ?? null,
            'oto_labels' => $result['labels'] ?? (!empty($result['labelUrl']) ? [$result['labelUrl']] : null),
            'oto_response' => array_merge($otoResponse, ['create_shipment' => $result]),
        ]);

        // ارجع الـ response JSON
        return $result;
    }
}

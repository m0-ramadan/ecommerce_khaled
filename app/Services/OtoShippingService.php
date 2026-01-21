<?php

namespace App\Services;

use App\Models\District;
use App\Models\SaudiCity;
use App\Models\ShippingOrder;
use App\Models\ShippingPrice;
use App\Models\ShipmentTracking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OtoShippingService
{
    private $baseUrl;
    private $apiKey;
    private $merchantId;
    private $branchId;

    public function __construct()
    {
        $this->baseUrl = config('services.oto.base_url', 'https://api.oto.sa/api/v1');
        $this->apiKey = config('services.oto.api_key');
        $this->merchantId = config('services.oto.merchant_id');
        $this->branchId = config('services.oto.branch_id');
    }

    /**
     * الحصول على المدن السعودية من OTO
     */
    public function fetchCities()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/cities');

            if ($response->successful()) {
                $cities = $response->json();
                
                // حفظ المدن في قاعدة البيانات
                foreach ($cities['data'] ?? [] as $city) {
                    SaudiCity::updateOrCreate(
                        ['oto_city_code' => $city['id']],
                        [
                            'name_ar' => $city['name_ar'] ?? $city['name'],
                            'name_en' => $city['name_en'] ?? null,
                            'region_ar' => $city['region_ar'] ?? null,
                            'region_en' => $city['region_en'] ?? null,
                            'is_active' => true
                        ]
                    );
                }
                
                return true;
            }
            
            Log::error('Failed to fetch cities from OTO', ['response' => $response->body()]);
            return false;
            
        } catch (\Exception $e) {
            Log::error('Error fetching cities from OTO', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * الحصول على الأحياء/المناطق لمدينة معينة
     */
    public function fetchDistricts($cityCode)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/cities/' . $cityCode . '/districts');

            if ($response->successful()) {
                $districts = $response->json();
                
                $city = SaudiCity::where('oto_city_code', $cityCode)->first();
                if (!$city) return false;
                
                foreach ($districts['data'] ?? [] as $district) {
                    District::updateOrCreate(
                        ['oto_district_code' => $district['id']],
                        [
                            'city_id' => $city->id,
                            'name_ar' => $district['name_ar'] ?? $district['name'],
                            'name_en' => $district['name_en'] ?? null,
                            'postal_code' => $district['postal_code'] ?? null,
                            'additional_code' => $district['additional_code'] ?? null,
                            'is_active' => true
                        ]
                    );
                }
                
                return true;
            }
            
            Log::error('Failed to fetch districts from OTO', [
                'city_code' => $cityCode,
                'response' => $response->body()
            ]);
            return false;
            
        } catch (\Exception $e) {
            Log::error('Error fetching districts from OTO', [
                'city_code' => $cityCode,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * الحصول على خدمات الشحن المتاحة
     */
    public function fetchShippingServices()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/services');

            if ($response->successful()) {
                $services = $response->json();
                
                foreach ($services['data'] ?? [] as $service) {
                    \App\Models\ShippingService::updateOrCreate(
                        ['code' => $service['code']],
                        [
                            'name_ar' => $service['name_ar'] ?? $service['name'],
                            'name_en' => $service['name_en'] ?? null,
                            'description_ar' => $service['description_ar'] ?? null,
                            'description_en' => $service['description_en'] ?? null,
                            'base_price' => $service['base_price'] ?? 0,
                            'delivery_days' => $service['delivery_days'] ?? null,
                            'features' => $service['features'] ?? [],
                            'is_active' => $service['is_active'] ?? true
                        ]
                    );
                }
                
                return true;
            }
            
            Log::error('Failed to fetch shipping services from OTO', ['response' => $response->body()]);
            return false;
            
        } catch (\Exception $e) {
            Log::error('Error fetching shipping services from OTO', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * حساب تكلفة الشحن
     */
    public function calculateShippingCost($data)
    {
        try {
            $requestData = [
                'from_city' => $data['from_city'],
                'to_city' => $data['to_city'],
                'service_code' => $data['service_code'],
                'weight' => $data['weight'],
                'pieces' => $data['pieces'] ?? 1,
                'cod_value' => $data['cod_value'] ?? 0,
                'declared_value' => $data['declared_value'] ?? 0,
                'dimensions' => $data['dimensions'] ?? null
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/calculate', $requestData);

            if ($response->successful()) {
                return $response->json();
            }
            
            Log::error('Failed to calculate shipping cost', [
                'data' => $data,
                'response' => $response->body()
            ]);
            return null;
            
        } catch (\Exception $e) {
            Log::error('Error calculating shipping cost', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * إنشاء طلب شحن جديد
     */
    public function createShippingOrder(ShippingOrder $shippingOrder)
    {
        try {
            $orderData = $this->prepareOrderData($shippingOrder);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/orders', $orderData);

            if ($response->successful()) {
                $result = $response->json();
                
                // تحديث طلب الشحن بمعلومات OTO
                $shippingOrder->update([
                    'oto_order_id' => $result['data']['order_id'],
                    'oto_tracking_number' => $result['data']['tracking_number'],
                    'status' => 'created',
                    'oto_response' => $result,
                    'oto_labels' => [
                        'label_url' => $result['data']['label_url'] ?? null,
                        'label_base64' => $result['data']['label_base64'] ?? null
                    ]
                ]);
                
                // تسجيل التتبع الأول
                $this->logTrackingEvent($shippingOrder, 'created', 'تم إنشاء طلب الشحن في نظام OTO');
                
                return $result;
            }
            
            Log::error('Failed to create shipping order in OTO', [
                'order_id' => $shippingOrder->id,
                'response' => $response->body(),
                'request_data' => $orderData
            ]);
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Error creating shipping order in OTO', [
                'order_id' => $shippingOrder->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * تحضير بيانات الطلب لإرسالها لـ OTO
     */
    private function prepareOrderData(ShippingOrder $shippingOrder)
    {
        // الحصول على أكواد المدن
        $senderCity = SaudiCity::where('name_ar', $shippingOrder->sender_city)->first();
        $receiverCity = SaudiCity::where('name_ar', $shippingOrder->receiver_city)->first();
        
        $data = [
            'merchant_id' => $this->merchantId,
            'branch_id' => $this->branchId,
            'service_code' => $shippingOrder->shippingService->code,
            
            // بيانات المرسل
            'sender' => [
                'name' => $shippingOrder->sender_name,
                'phone' => $this->formatPhone($shippingOrder->sender_phone),
                'email' => $shippingOrder->sender_email,
                'city_code' => $senderCity->oto_city_code ?? null,
                'district' => $shippingOrder->sender_district,
                'address' => $shippingOrder->sender_address,
                'postal_code' => $shippingOrder->sender_postal_code
            ],
            
            // بيانات المستلم
            'receiver' => [
                'name' => $shippingOrder->receiver_name,
                'phone' => $this->formatPhone($shippingOrder->receiver_phone),
                'email' => $shippingOrder->receiver_email,
                'city_code' => $receiverCity->oto_city_code ?? null,
                'district' => $shippingOrder->receiver_district,
                'address' => $shippingOrder->receiver_address,
                'postal_code' => $shippingOrder->receiver_postal_code
            ],
            
            // تفاصيل الشحنة
            'shipment' => [
                'pieces' => $shippingOrder->pieces_count,
                'weight' => $shippingOrder->weight,
                'length' => $shippingOrder->length,
                'width' => $shippingOrder->width,
                'height' => $shippingOrder->height,
                'declared_value' => $shippingOrder->declared_value,
                'content_type' => $shippingOrder->content_type,
                'content_description' => $shippingOrder->content_description
            ],
            
            // معلومات الدفع
            'payment' => [
                'type' => $shippingOrder->payment_type,
                'cod_amount' => $shippingOrder->cash_on_delivery_amount,
                'shipping_cost' => $shippingOrder->shipping_cost,
                'insurance_amount' => $shippingOrder->insurance_amount
            ],
            
            // معلومات إضافية
            'notes' => $shippingOrder->notes,
            'reference_number' => 'ORDER-' . $shippingOrder->order_id,
            'return_address' => $shippingOrder->sender_address
        ];
        
        // إزالة القيم الفارغة
        return array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });
    }

    /**
     * تتبع الشحنة
     */
    public function trackShipment($trackingNumber)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/track/' . $trackingNumber);

            if ($response->successful()) {
                $result = $response->json();
                
                // العثور على طلب الشحن وتحديث حالته
                $shippingOrder = ShippingOrder::where('oto_tracking_number', $trackingNumber)->first();
                
                if ($shippingOrder && isset($result['data']['status'])) {
                    $shippingOrder->update([
                        'status' => $this->mapOtoStatus($result['data']['status'])
                    ]);
                    
                    // تسجيل أحداث التتبع
                    if (isset($result['data']['tracking_events'])) {
                        foreach ($result['data']['tracking_events'] as $event) {
                            $this->logTrackingEvent($shippingOrder, 
                                $this->mapOtoStatus($event['status']),
                                $event['description'],
                                $event['location'] ?? null,
                                $event['event_date'] ?? null
                            );
                        }
                    }
                }
                
                return $result;
            }
            
            Log::error('Failed to track shipment', [
                'tracking_number' => $trackingNumber,
                'response' => $response->body()
            ]);
            return null;
            
        } catch (\Exception $e) {
            Log::error('Error tracking shipment', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * طباعة ملصق الشحن
     */
    public function printLabel($trackingNumber, $format = 'pdf')
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/' . $format,
            ])->get($this->baseUrl . '/labels/' . $trackingNumber . '.' . $format);

            if ($response->successful()) {
                return [
                    'content' => $response->body(),
                    'content_type' => $response->header('Content-Type'),
                    'format' => $format
                ];
            }
            
            Log::error('Failed to print label', [
                'tracking_number' => $trackingNumber,
                'format' => $format,
                'response' => $response->status()
            ]);
            return null;
            
        } catch (\Exception $e) {
            Log::error('Error printing label', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * إلغاء طلب الشحن
     */
    public function cancelShippingOrder($trackingNumber, $reason = '')
    {
        try {
            $data = ['reason' => $reason];
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/orders/' . $trackingNumber . '/cancel', $data);

            if ($response->successful()) {
                $result = $response->json();
                
                // تحديث حالة طلب الشحن
                $shippingOrder = ShippingOrder::where('oto_tracking_number', $trackingNumber)->first();
                if ($shippingOrder) {
                    $shippingOrder->update(['status' => 'cancelled']);
                    $this->logTrackingEvent($shippingOrder, 'cancelled', 'تم إلغاء طلب الشحن: ' . $reason);
                }
                
                return $result;
            }
            
            Log::error('Failed to cancel shipping order', [
                'tracking_number' => $trackingNumber,
                'response' => $response->body()
            ]);
            return null;
            
        } catch (\Exception $e) {
            Log::error('Error canceling shipping order', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * تسجيل حدث تتبع
     */
    private function logTrackingEvent($shippingOrder, $status, $description, $location = null, $eventDate = null)
    {
        // تعيين الترجمة العربية للحالة
        $statusAr = $this->getStatusArabic($status);
        $descriptionAr = $this->getDescriptionArabic($description);
        
        ShipmentTracking::create([
            'shipping_order_id' => $shippingOrder->id,
            'status' => $status,
            'status_ar' => $statusAr,
            'description' => $description,
            'description_ar' => $descriptionAr,
            'location' => $location,
            'event_date' => $eventDate ?? now()
        ]);
    }

    /**
     * تحويل حالة OTO لحالة النظام
     */
    private function mapOtoStatus($otoStatus)
    {
        $statusMap = [
            'pending' => 'pending',
            'accepted' => 'created',
            'picked_up' => 'picked_up',
            'in_transit' => 'in_transit',
            'out_for_delivery' => 'in_transit',
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
            'returned' => 'returned',
            'on_hold' => 'on_hold'
        ];
        
        return $statusMap[$otoStatus] ?? 'pending';
    }

    /**
     * الحصول على الترجمة العربية للحالة
     */
    private function getStatusArabic($status)
    {
        $arabicStatus = [
            'pending' => 'قيد الانتظار',
            'created' => 'تم الإنشاء',
            'picked_up' => 'تم الاستلام',
            'in_transit' => 'قيد النقل',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغي',
            'returned' => 'مرتجع',
            'on_hold' => 'معلق'
        ];
        
        return $arabicStatus[$status] ?? $status;
    }

    /**
     * الحصول على الترجمة العربية للوصف
     */
    private function getDescriptionArabic($description)
    {
        $arabicDescriptions = [
            'Shipment created successfully' => 'تم إنشاء الشحنة بنجاح',
            'Shipment picked up from sender' => 'تم استلام الشحنة من المرسل',
            'Shipment in transit' => 'الشحنة قيد النقل',
            'Out for delivery' => 'الشحنة في طريقها للتوصيل',
            'Delivered successfully' => 'تم التوصيل بنجاح',
            'Shipment cancelled' => 'تم إلغاء الشحنة',
            'Shipment returned to sender' => 'تم إرجاع الشحنة للمرسل',
            'Shipment on hold' => 'الشحنة معلقة'
        ];
        
        return $arabicDescriptions[$description] ?? $description;
    }

    /**
     * تنسيق رقم الهاتف
     */
    private function formatPhone($phone)
    {
        // إزالة أي أحرف غير رقمية
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // إضافة رمز الدولة إذا لم يكن موجوداً
        if (strpos($phone, '966') !== 0 && strlen($phone) == 9) {
            $phone = '966' . $phone;
        }
        
        return $phone;
    }

    /**
     * تحديث أوزان المنتجات من الطلبات
     */
    public function updateProductWeightsFromOrders()
    {
        try {
            $orders = \App\Models\Order::whereDoesntHave('shippingOrder')
                ->where('status', '!=', 'cancelled')
                ->with('items.product')
                ->get();
            
            foreach ($orders as $order) {
                $totalWeight = 0;
                $piecesCount = 0;
                
                foreach ($order->items as $item) {
                    // حساب الوزن بناءً على المنتج والمقاس والكمية
                    $productWeight = $item->product->weight ?? 0.5; // وزن افتراضي 0.5 كجم
                    $sizeMultiplier = $this->getSizeMultiplier($item->size_id);
                    
                    $totalWeight += ($productWeight * $sizeMultiplier * $item->quantity);
                    $piecesCount += $item->quantity;
                }
                
                // إذا كان الوزن صفراً، استخدم الوزن الافتراضي
                if ($totalWeight <= 0) {
                    $totalWeight = max(0.5, $piecesCount * 0.3);
                }
                
                // إنشاء طلب الشحن
                $shippingOrder = ShippingOrder::create([
                    'order_id' => $order->id,
                    'shipping_service_id' => 1, // الخدمة الافتراضية
                    'status' => 'pending',
                    
                    // بيانات المرسل (من متجرك)
                    'sender_name' => config('app.name'),
                    'sender_phone' => config('app.phone'),
                    'sender_city' => 'الرياض', // المدينة الرئيسية
                    'sender_district' => 'حي السليمانية', // الحي الرئيسي
                    'sender_address' => config('app.address'),
                    
                    // بيانات المستلم (من الطلب)
                    'receiver_name' => $order->customer_name,
                    'receiver_phone' => $order->customer_phone,
                    'receiver_email' => $order->customer_email,
                    'receiver_city' => $this->extractCityFromAddress($order->shipping_address),
                    'receiver_district' => $this->extractDistrictFromAddress($order->shipping_address),
                    'receiver_address' => $order->shipping_address,
                    
                    // تفاصيل الشحنة
                    'pieces_count' => $piecesCount,
                    'weight' => $totalWeight,
                    'declared_value' => $order->total_amount,
                    'content_type' => 'clothing',
                    'content_description' => 'ملابس ومستلزمات',
                    
                    // معلومات الدفع
                    'payment_type' => 'cash_on_delivery',
                    'cash_on_delivery_amount' => $order->total_amount,
                    'shipping_cost' => 0, // سيتم حسابها لاحقاً
                    'total_amount' => $order->total_amount,
                    
                    'notes' => 'طلب رقم: ' . $order->order_number
                ]);
                
                // حساب تكلفة الشحن الفعلية
                $this->calculateAndUpdateShippingCost($shippingOrder);
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error updating product weights from orders', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * حساب مضاعف المقاس
     */
    private function getSizeMultiplier($sizeId)
    {
        if (!$sizeId) return 1;
        
        $size = \App\Models\Size::find($sizeId);
        if (!$size) return 1;
        
        $sizeName = strtolower($size->name);
        
        $multipliers = [
            'xs' => 0.8,
            's' => 0.9,
            'm' => 1,
            'l' => 1.1,
            'xl' => 1.2,
            'xxl' => 1.3,
            'xxxl' => 1.4,
            'صغير' => 0.9,
            'وسط' => 1,
            'كبير' => 1.2,
            'كبير جداً' => 1.4
        ];
        
        foreach ($multipliers as $key => $value) {
            if (strpos($sizeName, $key) !== false) {
                return $value;
            }
        }
        
        return 1;
    }

    /**
     * استخراج المدينة من العنوان
     */
    private function extractCityFromAddress($address)
    {
        $saudiCities = SaudiCity::active()->pluck('name_ar')->toArray();
        
        foreach ($saudiCities as $city) {
            if (strpos($address, $city) !== false) {
                return $city;
            }
        }
        
        return 'الرياض'; // المدينة الافتراضية
    }

    /**
     * استخراج الحي من العنوان
     */
    private function extractDistrictFromAddress($address)
    {
        // يمكن تحسين هذا المنطق حسب احتياجاتك
        $commonDistricts = ['حي', 'شارع', 'حي', 'الحي', 'مقاطعة'];
        
        foreach ($commonDistricts as $district) {
            if (strpos($address, $district) !== false) {
                $parts = explode($district, $address, 2);
                if (isset($parts[1])) {
                    $districtName = trim(explode(',', $parts[1])[0]);
                    if (!empty($districtName)) {
                        return $district;
                    }
                }
            }
        }
        
        return 'حي السليمانية'; // الحي الافتراضي
    }

    /**
     * حساب وتحديث تكلفة الشحن
     */
    public function calculateAndUpdateShippingCost(ShippingOrder $shippingOrder)
    {
        try {
            $fromCity = SaudiCity::where('name_ar', $shippingOrder->sender_city)->first();
            $toCity = SaudiCity::where('name_ar', $shippingOrder->receiver_city)->first();
            
            if (!$fromCity || !$toCity) {
                Log::warning('Cities not found for shipping cost calculation', [
                    'from' => $shippingOrder->sender_city,
                    'to' => $shippingOrder->receiver_city
                ]);
                return false;
            }
            
            // البحث عن سعر الشحن المناسب
            $shippingPrice = ShippingPrice::where('from_city_id', $fromCity->id)
                ->where('to_city_id', $toCity->id)
                ->where('shipping_service_id', $shippingOrder->shipping_service_id)
                ->active()
                ->first();
            
            if ($shippingPrice) {
                $cost = $shippingPrice->calculateShippingCost(
                    $shippingOrder->weight,
                    $shippingOrder->cash_on_delivery_amount,
                    $shippingOrder->declared_value
                );
                
                $shippingOrder->update([
                    'shipping_cost' => $cost,
                    'total_amount' => $shippingOrder->cash_on_delivery_amount + $cost
                ]);
                
                return true;
            }
            
            // إذا لم يوجد سعر محدد، استخدم حساب OTO
            $calculation = $this->calculateShippingCost([
                'from_city' => $fromCity->oto_city_code,
                'to_city' => $toCity->oto_city_code,
                'service_code' => $shippingOrder->shippingService->code,
                'weight' => $shippingOrder->weight,
                'pieces' => $shippingOrder->pieces_count,
                'cod_value' => $shippingOrder->cash_on_delivery_amount,
                'declared_value' => $shippingOrder->declared_value
            ]);
            
            if ($calculation && isset($calculation['data']['total_cost'])) {
                $shippingOrder->update([
                    'shipping_cost' => $calculation['data']['total_cost'],
                    'total_amount' => $shippingOrder->cash_on_delivery_amount + $calculation['data']['total_cost']
                ]);
                
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::error('Error calculating shipping cost', [
                'shipping_order_id' => $shippingOrder->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    
    /**
     * تحديث تتبع جميع الشحنات
     */
    public function updateAllShipmentsTracking()
    {
        try {
            $shippingOrders = ShippingOrder::whereNotIn('status', ['delivered', 'cancelled', 'returned'])
                ->whereNotNull('oto_tracking_number')
                ->get();
            
            foreach ($shippingOrders as $order) {
                $this->trackShipment($order->oto_tracking_number);
                
                // تأخير بين الطلبات لتجنب rate limits
                usleep(500000); // 0.5 ثانية
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error updating all shipments tracking', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * إنشاء تقرير الشحنات
     */
    public function generateShippingReport($startDate, $endDate, $status = null)
    {
        $query = ShippingOrder::with(['order', 'shippingService'])
            ->whereBetween('created_at', [$startDate, $endDate]);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $shippingOrders = $query->get();
        
        $report = [
            'total_shipments' => $shippingOrders->count(),
            'total_revenue' => $shippingOrders->sum('shipping_cost'),
            'total_cod_amount' => $shippingOrders->sum('cash_on_delivery_amount'),
            'by_status' => $shippingOrders->groupBy('status')->map->count(),
            'by_service' => $shippingOrders->groupBy('shipping_service_id')->map->count(),
            'by_city' => $shippingOrders->groupBy('receiver_city')->map->count(),
            'shipments' => $shippingOrders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order->order_number ?? 'N/A',
                    'tracking_number' => $order->oto_tracking_number,
                    'status' => $order->status,
                    'status_label' => $order->status_label,
                    'receiver_city' => $order->receiver_city,
                    'receiver_name' => $order->receiver_name,
                    'weight' => $order->weight,
                    'shipping_cost' => $order->shipping_cost,
                    'cod_amount' => $order->cash_on_delivery_amount,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'estimated_delivery' => $order->estimated_delivery_date?->format('Y-m-d')
                ];
            })
        ];
        
        return $report;
    }
}
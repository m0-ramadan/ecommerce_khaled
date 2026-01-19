<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\ShippingOrder;
use App\Models\ShipmentTracking;
use App\Models\SaudiCity;
use App\Models\District;
use App\Models\UserAddress;

class OtoV2ShippingService
{
    private $baseUrl;
    private $apiKey;
    private $apiVersion = 'v2';
    private $companyCode;
    private $brandId;

    // حالات الشحن من OTO V2
    private $statusMappings = [
        'new' => 'new',
        'missingData' => 'pending',
        'paymentConfirmed' => 'payment_confirmed',
        'waitingAddressConfirmation' => 'pending',
        'addressConfirmed' => 'address_confirmed',
        'orderConfirmed' => 'order_confirmed',
        'shipmentCreated' => 'shipment_created',
        'goingToPickup' => 'going_to_pickup',
        'arrivedPickup' => 'arrived_pickup',
        'pickedUp' => 'picked_up',
        'inTransit' => 'in_transit',
        'outForDelivery' => 'out_for_delivery',
        'delivered' => 'delivered',
        'undeliveredAttempt' => 'undelivered',
        'canceled' => 'cancelled',
        'returned' => 'returned',
        'shipmentOnHold' => 'on_hold',
        'lostOrDamaged' => 'lost_damaged'
    ];

    public function __construct()
    {
        $this->baseUrl = config('services.oto.base_url', 'https://api.tryoto.com');
        $this->apiKey = config('services.oto.api_key');
        $this->companyCode = config('services.oto.company_code');
        $this->brandId = config('services.oto.brand_id');
    }

    /**
     * إنشاء شحنة جديدة في OTO V2
     */
    public function createShipment($orderData)
    {
        try {
            $requestData = $this->prepareShipmentData($orderData);

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(60)
                ->post("{$this->baseUrl}/rest/{$this->apiVersion}/create", $requestData);

            if ($response->successful()) {
                $result = $response->json();

                // معالجة الاستجابة الناجحة
                if (isset($result['orderId'])) {
                    return [
                        'success' => true,
                        'data' => $result,
                        'order_id' => $result['orderId'],
                        'tracking_number' => $result['trackingNumber'] ?? null,
                        'label_url' => $result['labelUrl'] ?? null
                    ];
                }
            }

            // معالجة الأخطاء
            return $this->handleApiError($response, 'create shipment');
        } catch (\Exception $e) {
            Log::error('Error creating shipment in OTO V2', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'OTO_API_ERROR',
                'message' => 'فشل الاتصال بخدمة OTO: ' . $e->getMessage()
            ];
        }
    }

    /**
     * تحضير بيانات الشحنة لـ OTO V2
     */
    private function prepareShipmentData($orderData)
    {
        $requestData = [
            'orderId' => $orderData['order_number'] ?? $this->generateOrderId(),
            'sender' => $this->prepareSenderData(),
            'recipient' => $this->prepareRecipientData($orderData),
            'shipment' => $this->prepareShipmentDetails($orderData),
            'payment' => $this->preparePaymentData($orderData),
            'service' => $this->prepareServiceData($orderData)
        ];

        // إضافة الصناديق إذا كانت موجودة
        if (isset($orderData['boxes']) && is_array($orderData['boxes'])) {
            $requestData['boxes'] = $orderData['boxes'];
        }

        // إضافة العناصر إذا كانت موجودة
        if (isset($orderData['items']) && is_array($orderData['items'])) {
            $requestData['items'] = $orderData['items'];
        }

        return $requestData;
    }

    /**
     * تحضير بيانات المرسل
     */
    private function prepareSenderData()
    {
        return [
            'name' => config('services.oto.sender_name', config('app.name')),
            'phone' => $this->formatPhone(config('services.oto.sender_phone')),
            'email' => config('services.oto.sender_email'),
            'company' => config('services.oto.company_name', ''),
            'country' => 'SA',
            'city' => config('services.oto.sender_city', 'Riyadh'),
            'address' => config('services.oto.sender_address', ''),
            'postalCode' => config('services.oto.sender_postal_code', '')
        ];
    }

    /**
     * تحضير بيانات المستلم
     */
    private function prepareRecipientData($orderData)
    {
        return [
            'name' => $orderData['receiver_name'] ?? '',
            'phone' => $this->formatPhone($orderData['receiver_phone'] ?? ''),
            'email' => $orderData['receiver_email'] ?? '',
            'country' => 'SA',
            'city' => $orderData['receiver_city'] ?? '',
            'address' => $orderData['receiver_address'] ?? '',
            'district' => $orderData['receiver_district'] ?? '',
            'postalCode' => $orderData['receiver_postal_code'] ?? '',
            'shortAddressCode' => $orderData['short_address_code'] ?? null
        ];
    }

    /**
     * تحضير تفاصيل الشحنة
     */
    private function prepareShipmentDetails($orderData)
    {
        return [
            'packageCount' => $orderData['pieces_count'] ?? 1,
            'packageWeight' => $orderData['weight'] ?? 0.5,
            'declaredValue' => $orderData['declared_value'] ?? 0,
            'description' => $orderData['content_description'] ?? 'Products',
            'dimensions' => [
                'length' => $orderData['length'] ?? 20,
                'width' => $orderData['width'] ?? 20,
                'height' => $orderData['height'] ?? 20,
                'unit' => 'cm'
            ],
            'notes' => $orderData['notes'] ?? ''
        ];
    }

    /**
     * تحضير بيانات الدفع
     */
    private function preparePaymentData($orderData)
    {
        $paymentType = $orderData['payment_type'] ?? 'cod';

        return [
            'type' => $paymentType,
            'codAmount' => $paymentType === 'cod' ? ($orderData['cod_amount'] ?? 0) : 0,
            'whoPays' => $orderData['who_pays'] ?? 'sender' // sender or recipient
        ];
    }

    /**
     * تحضير بيانات الخدمة
     */
    private function prepareServiceData($orderData)
    {
        return [
            'deliveryCompanyCode' => $orderData['delivery_company'] ?? 'aramex',
            'serviceType' => $orderData['service_type'] ?? 'standard',
            'pickupLocation' => $orderData['pickup_location'] ?? null,
            'deliveryType' => $orderData['delivery_type'] ?? 'delivery' // delivery or pickup
        ];
    }

    /**
     * تتبع الشحنة
     */
    public function trackShipment($trackingNumber)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/rest/{$this->apiVersion}/track", [
                    'trackingNumber' => $trackingNumber
                ]);

            if ($response->successful()) {
                $result = $response->json();

                // تحديث حالة الشحنة في قاعدة البيانات
                $this->updateShipmentStatus($trackingNumber, $result);

                return [
                    'success' => true,
                    'data' => $result,
                    'status' => $this->mapStatus($result['status'] ?? 'unknown'),
                    'tracking_events' => $result['trackingEvents'] ?? []
                ];
            }

            return $this->handleApiError($response, 'track shipment');
        } catch (\Exception $e) {
            Log::error('Error tracking shipment in OTO V2', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'TRACKING_ERROR',
                'message' => 'فشل تتبع الشحنة'
            ];
        }
    }

    /**
     * تحديث حالة الشحنة
     */
    private function updateShipmentStatus($trackingNumber, $trackingData)
    {
        try {
            $shippingOrder = ShippingOrder::where('oto_tracking_number', $trackingNumber)->first();

            if ($shippingOrder && isset($trackingData['status'])) {
                $mappedStatus = $this->mapStatus($trackingData['status']);

                $shippingOrder->update([
                    'status' => $mappedStatus,
                    'oto_response' => array_merge(
                        $shippingOrder->oto_response ?? [],
                        ['last_tracking' => $trackingData]
                    ),
                ]);

                // تسجيل أحداث التتبع
                if (isset($trackingData['trackingEvents']) && is_array($trackingData['trackingEvents'])) {
                    foreach ($trackingData['trackingEvents'] as $event) {
                        $this->logTrackingEvent(
                            $shippingOrder,
                            $event['status'] ?? 'unknown',
                            $event['description'] ?? '',
                            $event['location'] ?? null,
                            $event['timestamp'] ?? now()
                        );
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error updating shipment status', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * الحصول على تتبع السائق في الوقت الحقيقي
     */
    public function trackDriver($orderId)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post("{$this->baseUrl}/rest/{$this->apiVersion}/trackDriver", [
                    'orderId' => $orderId
                ]);

            if ($response->successful()) {
                $result = $response->json();

                return [
                    'success' => true,
                    'data' => $result,
                    'driver_name' => $result['driverName'] ?? null,
                    'current_location' => [
                        'lat' => $result['lat'] ?? null,
                        'lng' => $result['lng'] ?? null
                    ],
                    'locations_history' => $result['data'] ?? [],
                    'last_update' => $result['lastLatLonUpdated'] ?? null
                ];
            }

            return $this->handleApiError($response, 'track driver');
        } catch (\Exception $e) {
            Log::error('Error tracking driver in OTO V2', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'DRIVER_TRACKING_ERROR',
                'message' => 'فشل تتبع السائق'
            ];
        }
    }

    /**
     * إلغاء الشحنة
     */
    public function cancelShipment($trackingNumber, $reason = '')
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post("{$this->baseUrl}/rest/{$this->apiVersion}/cancel", [
                    'trackingNumber' => $trackingNumber,
                    'reason' => $reason ?: 'Canceled by merchant'
                ]);

            if ($response->successful()) {
                $result = $response->json();

                // تحديث حالة الشحنة
                $shippingOrder = ShippingOrder::where('oto_tracking_number', $trackingNumber)->first();
                if ($shippingOrder) {
                    $shippingOrder->update([
                        'status' => 'cancelled',
                        'notes' => $reason
                    ]);

                    $this->logTrackingEvent($shippingOrder, 'canceled', 'تم إلغاء الشحنة: ' . $reason);
                }

                return [
                    'success' => true,
                    'data' => $result,
                    'message' => 'تم إلغاء الشحنة بنجاح'
                ];
            }

            return $this->handleApiError($response, 'cancel shipment');
        } catch (\Exception $e) {
            Log::error('Error canceling shipment in OTO V2', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'CANCELLATION_ERROR',
                'message' => 'فشل إلغاء الشحنة'
            ];
        }
    }

    /**
     * الحصول على ملصق الشحن
     */
    public function getLabel($trackingNumber, $format = 'pdf')
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/rest/{$this->apiVersion}/label", [
                    'trackingNumber' => $trackingNumber,
                    'format' => $format
                ]);

            if ($response->successful()) {
                $contentType = $response->header('Content-Type');

                return [
                    'success' => true,
                    'content' => $response->body(),
                    'content_type' => $contentType,
                    'format' => $format
                ];
            }

            return $this->handleApiError($response, 'get label');
        } catch (\Exception $e) {
            Log::error('Error getting label in OTO V2', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'LABEL_ERROR',
                'message' => 'فشل الحصول على الملصق'
            ];
        }
    }

    /**
     * الحصول على تكلفة الشحن
     */
    public function calculateShipping($data)
    {
        try {
            $requestData = [
                'origin' => [
                    'country' => 'SA',
                    'city' => $data['from_city'] ?? 'Riyadh'
                ],
                'destination' => [
                    'country' => 'SA',
                    'city' => $data['to_city'] ?? 'Jeddah',
                    'district' => $data['district'] ?? ''
                ],
                'shipment' => [
                    'packageCount' => $data['pieces_count'] ?? 1,
                    'packageWeight' => $data['weight'] ?? 1,
                    'declaredValue' => $data['declared_value'] ?? 100,
                    'dimensions' => [
                        'length' => $data['length'] ?? 20,
                        'width' => $data['width'] ?? 20,
                        'height' => $data['height'] ?? 20,
                        'unit' => 'cm'
                    ]
                ],
                'payment' => [
                    'type' => $data['payment_type'] ?? 'cod',
                    'codAmount' => $data['cod_amount'] ?? 0
                ]
            ];

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post("{$this->baseUrl}/rest/{$this->apiVersion}/calculate", $requestData);

            if ($response->successful()) {
                $result = $response->json();

                return [
                    'success' => true,
                    'data' => $result,
                    'available_services' => $result['services'] ?? [],
                    'estimated_cost' => $result['estimatedCost'] ?? 0,
                    'estimated_days' => $result['estimatedDays'] ?? 3
                ];
            }

            return $this->handleApiError($response, 'calculate shipping');
        } catch (\Exception $e) {
            Log::error('Error calculating shipping in OTO V2', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'CALCULATION_ERROR',
                'message' => 'فشل حساب تكلفة الشحن'
            ];
        }
    }

    /**
     * الحصول على المدن المتاحة
     */
    public function getCities()
    {
        try {
            $cacheKey = 'oto_cities_' . date('Y-m-d');

            // التحقق من الكاش
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/rest/{$this->apiVersion}/cities");

            if ($response->successful()) {
                $result = $response->json();

                $cities = $this->processCitiesData($result);

                // حفظ في الكاش لمدة 24 ساعة
                Cache::put($cacheKey, $cities, now()->addHours(24));

                return $cities;
            }

            return $this->handleApiError($response, 'get cities');
        } catch (\Exception $e) {
            Log::error('Error getting cities from OTO V2', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'CITIES_ERROR',
                'message' => 'فشل جلب المدن'
            ];
        }
    }

    /**
     * معالجة بيانات المدن
     */
    private function processCitiesData($apiData)
    {
        $cities = [];

        if (isset($apiData['data']) && is_array($apiData['data'])) {
            foreach ($apiData['data'] as $city) {
                $cities[] = [
                    'id' => $city['cityCode'] ?? null,
                    'name_ar' => $city['nameAr'] ?? $city['name'],
                    'name_en' => $city['nameEn'] ?? $city['name'],
                    'region_ar' => $city['regionAr'] ?? '',
                    'region_en' => $city['regionEn'] ?? '',
                    'oto_city_code' => $city['cityCode'] ?? null,
                    'is_active' => $city['isActive'] ?? true
                ];
            }
        }

        return [
            'success' => true,
            'data' => $cities,
            'total' => count($cities)
        ];
    }

    /**
     * الحصول على المناطق/الأحياء
     */
    public function getDistricts($cityCode)
    {
        try {
            $cacheKey = "oto_districts_{$cityCode}_" . date('Y-m-d');

            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/rest/{$this->apiVersion}/cities/{$cityCode}/districts");

            if ($response->successful()) {
                $result = $response->json();

                $districts = $this->processDistrictsData($result);

                Cache::put($cacheKey, $districts, now()->addHours(24));

                return $districts;
            }

            return $this->handleApiError($response, 'get districts');
        } catch (\Exception $e) {
            Log::error('Error getting districts from OTO V2', [
                'city_code' => $cityCode,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'DISTRICTS_ERROR',
                'message' => 'فشل جلب المناطق'
            ];
        }
    }

    /**
     * معالجة بيانات المناطق
     */
    private function processDistrictsData($apiData)
    {
        $districts = [];

        if (isset($apiData['data']) && is_array($apiData['data'])) {
            foreach ($apiData['data'] as $district) {
                $districts[] = [
                    'id' => $district['districtCode'] ?? null,
                    'name_ar' => $district['nameAr'] ?? $district['name'],
                    'name_en' => $district['nameEn'] ?? $district['name'],
                    'postal_code' => $district['postalCode'] ?? '',
                    'additional_code' => $district['additionalCode'] ?? '',
                    'oto_district_code' => $district['districtCode'] ?? null,
                    'is_active' => $district['isActive'] ?? true
                ];
            }
        }

        return [
            'success' => true,
            'data' => $districts,
            'total' => count($districts)
        ];
    }

    /**
     * تسجيل حدث تتبع
     */
    private function logTrackingEvent($shippingOrder, $status, $description, $location = null, $eventDate = null)
    {
        try {
            ShipmentTracking::create([
                'shipping_order_id' => $shippingOrder->id,
                'status' => $status,
                'status_ar' => $this->getStatusArabic($status),
                'description' => $description,
                'description_ar' => $this->translateDescription($description),
                'location' => $location,
                'event_date' => $eventDate ?: now(),
                'oto_data' => [
                    'original_status' => $status,
                    'original_description' => $description
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error logging tracking event', [
                'shipping_order_id' => $shippingOrder->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * توليد معرف طلب فريد
     */
    private function generateOrderId()
    {
        $prefix = config('services.oto.order_prefix', 'OTO');
        $timestamp = now()->format('YmdHis');
        $random = rand(1000, 9999);

        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * تنسيق رقم الهاتف
     */
    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strpos($phone, '966') !== 0 && strlen($phone) == 9) {
            $phone = '966' . $phone;
        }

        return $phone;
    }

    /**
     * معالجة أخطاء API
     */
    private function handleApiError($response, $operation)
    {
        $statusCode = $response->status();
        $errorData = $response->json();

        Log::error("OTO API Error - {$operation}", [
            'status_code' => $statusCode,
            'error_data' => $errorData,
            'response_body' => $response->body()
        ]);

        $errorMessage = $this->getErrorMessage($errorData);

        return [
            'success' => false,
            'error' => 'OTO_API_ERROR',
            'status_code' => $statusCode,
            'oto_error_code' => $errorData['otoErrorCode'] ?? null,
            'oto_error_message' => $errorData['otoErrorMessage'] ?? null,
            'message' => $errorMessage
        ];
    }

    /**
     * الحصول على رسالة الخطأ
     */
    private function getErrorMessage($errorData)
    {
        $defaultMessage = 'حدث خطأ في خدمة الشحن';

        if (isset($errorData['otoErrorMessage'])) {
            return $errorData['otoErrorMessage'];
        }

        if (isset($errorData['message'])) {
            return $errorData['message'];
        }

        if (isset($errorData['error'])) {
            return is_array($errorData['error']) ? json_encode($errorData['error']) : $errorData['error'];
        }

        return $defaultMessage;
    }

    /**
     * Headers الـ API
     */
private function getHeaders()
{
    return [
        'Authorization' => 'Bearer ' . config('services.oto.token'),
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];
}


    /**
     * تحويل حالة OTO لحالة النظام
     */
    private function mapStatus($otoStatus)
    {
        return $this->statusMappings[$otoStatus] ?? 'unknown';
    }

    /**
     * الحصول على الترجمة العربية للحالة
     */
    private function getStatusArabic($status)
    {
        $arabicStatus = [
            'new' => 'جديد',
            'pending' => 'قيد الانتظار',
            'payment_confirmed' => 'تم تأكيد الدفع',
            'address_confirmed' => 'تم تأكيد العنوان',
            'order_confirmed' => 'تم تأكيد الطلب',
            'shipment_created' => 'تم إنشاء الشحنة',
            'going_to_pickup' => 'في طريق الاستلام',
            'arrived_pickup' => 'وصل لنقطة الاستلام',
            'picked_up' => 'تم الاستلام',
            'in_transit' => 'قيد النقل',
            'out_for_delivery' => 'في طريق التوصيل',
            'delivered' => 'تم التوصيل',
            'undelivered' => 'فشل التوصيل',
            'cancelled' => 'ملغي',
            'returned' => 'مرتجع',
            'on_hold' => 'معلق',
            'lost_damaged' => 'مفقود/تالف',
            'unknown' => 'غير معروف'
        ];

        return $arabicStatus[$status] ?? $status;
    }

    /**
     * ترجمة وصف الحدث
     */
    private function translateDescription($description)
    {
        $translations = [
            'Shipment created' => 'تم إنشاء الشحنة',
            'Picked up' => 'تم الاستلام',
            'In transit' => 'قيد النقل',
            'Out for delivery' => 'في طريق التوصيل',
            'Delivered' => 'تم التوصيل',
            'Delivery attempted' => 'تم محاولة التوصيل',
            'Shipment canceled' => 'تم إلغاء الشحنة',
            'Returned to sender' => 'مرتجع للمرسل',
            'Shipment on hold' => 'الشحنة معلقة'
        ];

        return $translations[$description] ?? $description;
    }

    /**
     * تحديث جميع الشحنات
     */
    public function updateAllShipments()
    {
        try {
            $shippingOrders = ShippingOrder::whereNotIn('status', ['delivered', 'cancelled', 'returned'])
                ->whereNotNull('oto_tracking_number')
                ->get();

            $updatedCount = 0;

            foreach ($shippingOrders as $order) {
                $result = $this->trackShipment($order->oto_tracking_number);

                if ($result['success']) {
                    $updatedCount++;
                }

                // تأخير 500ms بين الطلبات
                usleep(500000);
            }

            return [
                'success' => true,
                'updated_count' => $updatedCount,
                'total' => $shippingOrders->count()
            ];
        } catch (\Exception $e) {
            Log::error('Error updating all shipments', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'UPDATE_ERROR',
                'message' => 'فشل تحديث الشحنات'
            ];
        }
    }

    /**
     * إنشاء شحنة من عنوان المستخدم
     */
    public function createShipmentFromUserAddress($orderId, $addressId, $options = [])
    {
        try {
            // الحصول على الطلب والعنوان
            $order = \App\Models\Order::with('orderItems')->find($orderId);
            $address = UserAddress::find($addressId);

            if (!$order || !$address) {
                return [
                    'success' => false,
                    'error' => 'DATA_NOT_FOUND',
                    'message' => 'الطلب أو العنوان غير موجود'
                ];
            }

            // تحضير بيانات الشحنة
            $shipmentData = [
                'order_number' => $order->order_number ?? "ORDER-{$order->id}",
                'receiver_name' => $address->first_name . ' ' . $address->last_name,
                'receiver_phone' => $address->phone,
                'receiver_city' => $address->city,
                'receiver_district' => $address->area,
                'receiver_address' => $address->address_details,
                'weight' => $this->calculateOrderWeight($order),
                'pieces_count' => $order->orderItems->sum('quantity'),
                'declared_value' => $order->total_amount,
                'payment_type' => 'cod',
                'cod_amount' => $order->total_amount,
                'notes' => $options['notes'] ?? 'العنوان: ' . ($address->label ?? 'المنزل'),
                'who_pays' => $options['who_pays'] ?? 'sender',
                'delivery_company' => $options['delivery_company'] ?? 'aramex',
                'service_type' => $options['service_type'] ?? 'standard'
            ];

            // إضافة الأبعاد إذا كانت موجودة
            if (isset($options['dimensions'])) {
                $shipmentData['length'] = $options['dimensions']['length'] ?? 20;
                $shipmentData['width'] = $options['dimensions']['width'] ?? 20;
                $shipmentData['height'] = $options['dimensions']['height'] ?? 20;
            }

            // إنشاء الشحنة
            return $this->createShipment($shipmentData);
        } catch (\Exception $e) {
            Log::error('Error creating shipment from user address', [
                'order_id' => $orderId,
                'address_id' => $addressId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'SHIPMENT_CREATION_ERROR',
                'message' => 'فشل إنشاء الشحنة'
            ];
        }
    }

    /**
     * حساب وزن الطلب
     */
    private function calculateOrderWeight($order)
    {
        $totalWeight = 0;

        foreach ($order->items as $item) {
            $productWeight = $item->product->weight ?? 0.3; // وزن افتراضي 0.3 كجم
            $quantity = $item->quantity ?? 1;
            $totalWeight += ($productWeight * $quantity);
        }

        // الحد الأدنى للوزن 0.5 كجم
        return max(0.5, $totalWeight);
    }
}

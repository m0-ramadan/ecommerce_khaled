<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SaudiCity;
use App\Models\District;
use App\Models\ShippingService;
use App\Models\ShippingOrder;
use App\Models\ShipmentTracking;
use App\Services\OtoShippingService;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    protected $otoService;

    public function __construct(OtoShippingService $otoService)
    {
        $this->otoService = $otoService;
    }

    /**
     * @OA\Get(
     *     path="/api/shipping/cities",
     *     summary="الحصول على المدن السعودية",
     *     tags={"Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="قائمة المدن",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name_ar", type="string", example="الرياض"),
     *                     @OA\Property(property="name_en", type="string", example="Riyadh"),
     *                     @OA\Property(property="region_ar", type="string", example="منطقة الرياض"),
     *                     @OA\Property(property="region_en", type="string", example="Riyadh Region"),
     *                     @OA\Property(property="oto_city_code", type="string", example="RIY")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getCities(Request $request)
    {
        $cities = SaudiCity::active()
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'name_en', 'region_ar', 'region_en', 'oto_city_code']);
        
        return response()->json([
            'success' => true,
            'data' => $cities,
            'message' => 'تم جلب المدن بنجاح'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/shipping/cities/{cityId}/districts",
     *     summary="الحصول على أحياء مدينة معينة",
     *     tags={"Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="cityId",
     *         in="path",
     *         required=true,
     *         description="معرف المدينة",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="قائمة الأحياء",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name_ar", type="string", example="حي السليمانية"),
     *                     @OA\Property(property="name_en", type="string", example="Al Sulimaniyah"),
     *                     @OA\Property(property="postal_code", type="string", example="12211"),
     *                     @OA\Property(property="additional_code", type="string", example="001")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getDistricts($cityId)
    {
        $districts = District::where('city_id', $cityId)
            ->active()
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'name_en', 'postal_code', 'additional_code']);
        
        return response()->json([
            'success' => true,
            'data' => $districts,
            'message' => 'تم جلب الأحياء بنجاح'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/shipping/services",
     *     summary="الحصول على خدمات الشحن المتاحة",
     *     tags={"Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="قائمة خدمات الشحن",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="code", type="string", example="EXPRESS"),
     *                     @OA\Property(property="name_ar", type="string", example="خدمة سريعة"),
     *                     @OA\Property(property="name_en", type="string", example="Express Service"),
     *                     @OA\Property(property="base_price", type="number", format="float", example=25.00),
     *                     @OA\Property(property="delivery_days", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getShippingServices()
    {
        $services = ShippingService::active()
            ->orderBy('base_price')
            ->get(['id', 'code', 'name_ar', 'name_en', 'base_price', 'delivery_days']);
        
        return response()->json([
            'success' => true,
            'data' => $services,
            'message' => 'تم جلب خدمات الشحن بنجاح'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/shipping/calculate",
     *     summary="حساب تكلفة الشحن",
     *     tags={"Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"from_city_id", "to_city_id", "service_id", "weight"},
     *             @OA\Property(property="from_city_id", type="integer", example=1),
     *             @OA\Property(property="to_city_id", type="integer", example=2),
     *             @OA\Property(property="service_id", type="integer", example=1),
     *             @OA\Property(property="weight", type="number", format="float", example=2.5),
     *             @OA\Property(property="cod_amount", type="number", format="float", example=150.00),
     *             @OA\Property(property="declared_value", type="number", format="float", example=200.00),
     *             @OA\Property(property="pieces", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تكلفة الشحن",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="shipping_cost", type="number", format="float", example=35.50),
     *                 @OA\Property(property="estimated_days", type="integer", example=3),
     *                 @OA\Property(property="service_name", type="string", example="خدمة سريعة")
     *             )
     *         )
     *     )
     * )
     */
    public function calculateShippingCost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_city_id' => 'required|exists:saudi_cities,id',
            'to_city_id' => 'required|exists:saudi_cities,id',
            'service_id' => 'required|exists:shipping_services,id',
            'weight' => 'required|numeric|min:0.1',
            'cod_amount' => 'numeric|min:0',
            'declared_value' => 'numeric|min:0',
            'pieces' => 'integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'بيانات غير صالحة'
            ], 422);
        }

        $service = ShippingService::find($request->service_id);
        $fromCity = SaudiCity::find($request->from_city_id);
        $toCity = SaudiCity::find($request->to_city_id);

        // البحث عن السعر المحدد
        $shippingPrice = \App\Models\ShippingPrice::where('from_city_id', $request->from_city_id)
            ->where('to_city_id', $request->to_city_id)
            ->where('shipping_service_id', $request->service_id)
            ->active()
            ->first();

        if ($shippingPrice) {
            $cost = $shippingPrice->calculateShippingCost(
                $request->weight,
                $request->cod_amount ?? 0,
                $request->declared_value ?? 0
            );
            
            return response()->json([
                'success' => true,
                'data' => [
                    'shipping_cost' => $cost,
                    'estimated_days' => $shippingPrice->estimated_days,
                    'service_name' => $service->name,
                    'service_name_ar' => $service->name_ar,
                    'details' => [
                        'base_price' => $shippingPrice->base_price,
                        'per_kg_price' => $shippingPrice->per_kg_price,
                        'cod_percentage' => $shippingPrice->cod_percentage,
                        'insurance_percentage' => $shippingPrice->insurance_percentage
                    ]
                ],
                'message' => 'تم حساب تكلفة الشحن بنجاح'
            ]);
        }

        // إذا لم يوجد سعر محدد، استخدم خدمة OTO
        $otoData = [
            'from_city' => $fromCity->oto_city_code,
            'to_city' => $toCity->oto_city_code,
            'service_code' => $service->code,
            'weight' => $request->weight,
            'pieces' => $request->pieces ?? 1,
            'cod_value' => $request->cod_amount ?? 0,
            'declared_value' => $request->declared_value ?? 0
        ];

        $calculation = $this->otoService->calculateShippingCost($otoData);

        if ($calculation) {
            return response()->json([
                'success' => true,
                'data' => [
                    'shipping_cost' => $calculation['data']['total_cost'] ?? 0,
                    'estimated_days' => $calculation['data']['estimated_days'] ?? $service->delivery_days,
                    'service_name' => $service->name,
                    'service_name_ar' => $service->name_ar,
                    'details' => $calculation['data']
                ],
                'message' => 'تم حساب تكلفة الشحن بنجاح'
            ]);
        }

        // استخدام السعر الافتراضي
        return response()->json([
            'success' => true,
            'data' => [
                'shipping_cost' => $service->base_price,
                'estimated_days' => $service->delivery_days,
                'service_name' => $service->name,
                'service_name_ar' => $service->name_ar,
                'note' => 'تم استخدام السعر الافتراضي للخدمة'
            ],
            'message' => 'تم حساب تكلفة الشحن باستخدام السعر الافتراضي'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/shipping/orders/create",
     *     summary="إنشاء طلب شحن جديد",
     *     tags={"Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_id", "receiver_name", "receiver_phone", "receiver_city", "receiver_district", "receiver_address"},
     *             @OA\Property(property="order_id", type="integer", example=1),
     *             @OA\Property(property="service_id", type="integer", example=1),
     *             @OA\Property(property="receiver_name", type="string", example="أحمد محمد"),
     *             @OA\Property(property="receiver_phone", type="string", example="0501234567"),
     *             @OA\Property(property="receiver_email", type="string", format="email", example="ahmed@example.com"),
     *             @OA\Property(property="receiver_city", type="string", example="الرياض"),
     *             @OA\Property(property="receiver_district", type="string", example="حي السليمانية"),
     *             @OA\Property(property="receiver_address", type="string", example="شارع الملك فهد - مبنى 123"),
     *             @OA\Property(property="receiver_postal_code", type="string", example="12211"),
     *             @OA\Property(property="weight", type="number", format="float", example=2.5),
     *             @OA\Property(property="pieces_count", type="integer", example=1),
     *             @OA\Property(property="payment_type", type="string", enum={"cash_on_delivery", "prepaid"}, example="cash_on_delivery"),
     *             @OA\Property(property="notes", type="string", example="توصيل في المساء")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم إنشاء طلب الشحن",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="shipping_order_id", type="integer", example=1),
     *                 @OA\Property(property="oto_order_id", type="string", example="OTO-123456"),
     *                 @OA\Property(property="tracking_number", type="string", example="TRK-789012"),
     *                 @OA\Property(property="label_url", type="string", format="url", example="https://oto.sa/labels/TRK-789012.pdf"),
     *                 @OA\Property(property="shipping_cost", type="number", format="float", example=35.50)
     *             )
     *         )
     *     )
     * )
     */
    public function createShippingOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'service_id' => 'required|exists:shipping_services,id',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_email' => 'nullable|email|max:255',
            'receiver_city' => 'required|string|max:100',
            'receiver_district' => 'required|string|max:100',
            'receiver_address' => 'required|string|max:500',
            'receiver_postal_code' => 'nullable|string|max:10',
            'weight' => 'required|numeric|min:0.1',
            'pieces_count' => 'required|integer|min:1',
            'payment_type' => 'required|in:cash_on_delivery,prepaid,credit',
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'بيانات غير صالحة'
            ], 422);
        }

        try {
            $order = \App\Models\Order::with('items')->find($request->order_id);
            
            // إنشاء طلب الشحن
            $shippingOrder = ShippingOrder::create([
                'order_id' => $request->order_id,
                'shipping_service_id' => $request->service_id,
                'status' => 'pending',
                
                // بيانات المرسل (من إعدادات المتجر)
                'sender_name' => config('app.store_name', config('app.name')),
                'sender_phone' => config('app.store_phone', '0500000000'),
                'sender_email' => config('app.store_email'),
                'sender_city' => config('app.store_city', 'الرياض'),
                'sender_district' => config('app.store_district', 'حي السليمانية'),
                'sender_address' => config('app.store_address'),
                'sender_postal_code' => config('app.store_postal_code'),
                
                // بيانات المستلم (من الطلب)
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_email' => $request->receiver_email,
                'receiver_city' => $request->receiver_city,
                'receiver_district' => $request->receiver_district,
                'receiver_address' => $request->receiver_address,
                'receiver_postal_code' => $request->receiver_postal_code,
                
                // تفاصيل الشحنة
                'pieces_count' => $request->pieces_count,
                'weight' => $request->weight,
                'declared_value' => $order->total_amount,
                'content_type' => 'products',
                'content_description' => 'منتجات متجر ' . config('app.name'),
                
                // معلومات الدفع
                'payment_type' => $request->payment_type,
                'cash_on_delivery_amount' => $order->total_amount,
                'notes' => $request->notes
            ]);
            
            // حساب تكلفة الشحن
            $this->otoService->calculateAndUpdateShippingCost($shippingOrder);
            
            // إنشاء طلب الشحن في OTO
            $result = $this->otoService->createShippingOrder($shippingOrder);
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'shipping_order_id' => $shippingOrder->id,
                        'oto_order_id' => $shippingOrder->oto_order_id,
                        'tracking_number' => $shippingOrder->oto_tracking_number,
                        'label_url' => $shippingOrder->shipping_label_url,
                        'shipping_cost' => $shippingOrder->shipping_cost,
                        'status' => $shippingOrder->status,
                        'status_label' => $shippingOrder->status_label
                    ],
                    'message' => 'تم إنشاء طلب الشحن بنجاح'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل إنشاء طلب الشحن في نظام OTO'
                ], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء طلب الشحن: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/shipping/orders/{trackingNumber}/track",
     *     summary="تتبع الشحنة",
     *     tags={"Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="trackingNumber",
     *         in="path",
     *         required=true,
     *         description="رقم التتبع",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="معلومات تتبع الشحنة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="tracking_number", type="string", example="TRK-789012"),
     *                 @OA\Property(property="status", type="string", example="in_transit"),
     *                 @OA\Property(property="status_label", type="string", example="قيد النقل"),
     *                 @OA\Property(property="current_location", type="string", example="الرياض"),
     *                 @OA\Property(property="estimated_delivery", type="string", format="date", example="2024-01-15"),
     *                 @OA\Property(property="tracking_events", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="status", type="string", example="created"),
     *                         @OA\Property(property="status_label", type="string", example="تم الإنشاء"),
     *                         @OA\Property(property="description", type="string", example="تم إنشاء طلب الشحن"),
     *                         @OA\Property(property="location", type="string", example="الرياض"),
     *                         @OA\Property(property="event_date", type="string", format="date-time", example="2024-01-10T10:30:00Z")
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function trackShipment($trackingNumber)
    {
        try {
            $shippingOrder = ShippingOrder::where('oto_tracking_number', $trackingNumber)
                ->orWhere('id', $trackingNumber)
                ->first();
            
            if (!$shippingOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'رقم التتبع غير موجود'
                ], 404);
            }
            
            // تحديث معلومات التتبع من OTO
            $this->otoService->trackShipment($shippingOrder->oto_tracking_number);
            
            // إعادة تحميل بيانات الشحنة
            $shippingOrder->refresh();
            $trackingEvents = $shippingOrder->tracking()
                ->orderBy('event_date', 'desc')
                ->get()
                ->map(function($event) {
                    return [
                        'status' => $event->status,
                        'status_label' => $event->status_label,
                        'description' => $event->description_text,
                        'location' => $event->location,
                        'event_date' => $event->event_date->format('Y-m-d H:i:s')
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'tracking_number' => $shippingOrder->oto_tracking_number,
                    'order_number' => $shippingOrder->order->order_number ?? null,
                    'status' => $shippingOrder->status,
                    'status_label' => $shippingOrder->status_label,
                    'current_location' => $shippingOrder->currentTracking->location ?? null,
                    'estimated_delivery' => $shippingOrder->estimated_delivery_date?->format('Y-m-d'),
                    'receiver_name' => $shippingOrder->receiver_name,
                    'receiver_city' => $shippingOrder->receiver_city,
                    'shipping_cost' => $shippingOrder->shipping_cost,
                    'tracking_events' => $trackingEvents
                ],
                'message' => 'تم جلب معلومات التتبع بنجاح'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تتبع الشحنة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/shipping/orders/{trackingNumber}/label",
     *     summary="طباعة ملصق الشحن",
     *     tags={"Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="trackingNumber",
     *         in="path",
     *         required=true,
     *         description="رقم التتبع",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="format",
     *         in="query",
     *         required=false,
     *         description="صيغة الملصق (pdf أو png)",
     *         @OA\Schema(type="string", default="pdf", enum={"pdf", "png"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="ملصق الشحن",
     *         @OA\MediaType(
     *             mediaType="application/pdf",
     *             @OA\Schema(type="string", format="binary")
     *         ),
     *         @OA\MediaType(
     *             mediaType="image/png",
     *             @OA\Schema(type="string", format="binary")
     *         )
     *     )
     * )
     */
    public function printLabel($trackingNumber, Request $request)
    {
        try {
            $format = $request->get('format', 'pdf');
            
            $label = $this->otoService->printLabel($trackingNumber, $format);
            
            if ($label) {
                return response($label['content'])
                    ->header('Content-Type', $label['content_type'])
                    ->header('Content-Disposition', 'inline; filename="shipping_label_' . $trackingNumber . '.' . $format . '"');
            }
            
            // إذا فشلت طباعة الملصق من OTO، عرض معلومات بديلة
            $shippingOrder = ShippingOrder::where('oto_tracking_number', $trackingNumber)->first();
            
            if (!$shippingOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'رقم التتبع غير موجود'
                ], 404);
            }
            
            // إنشاء ملصق بديل (PDF)
            return $this->generateAlternativeLabel($shippingOrder);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء طباعة الملصق: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/shipping/orders/{trackingNumber}/cancel",
     *     summary="إلغاء طلب الشحن",
     *     tags={"Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="trackingNumber",
     *         in="path",
     *         required=true,
     *         description="رقم التتبع",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="reason", type="string", example="رغبة العميل")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم الإلغاء بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إلغاء طلب الشحن بنجاح")
     *         )
     *     )
     * )
     */
    public function cancelShippingOrder($trackingNumber, Request $request)
    {
        try {
            $shippingOrder = ShippingOrder::where('oto_tracking_number', $trackingNumber)
                ->whereNotIn('status', ['delivered', 'cancelled'])
                ->first();
            
            if (!$shippingOrder) {
                return response()->json([
                    'success' => false,
                    'message' => 'طلب الشحن غير موجود أو لا يمكن إلغاؤه'
                ], 404);
            }
            
            $reason = $request->get('reason', 'رغبة العميل');
            $result = $this->otoService->cancelShippingOrder($trackingNumber, $reason);
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إلغاء طلب الشحن بنجاح'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'فشل إلغاء طلب الشحن في نظام OTO'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إلغاء طلب الشحن: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/shipping/orders",
     *     summary="الحصول على قائمة طلبات الشحن",
     *     tags={"Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="حالة الشحن",
     *         @OA\Schema(type="string", enum={"pending", "created", "picked_up", "in_transit", "delivered", "cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=false,
     *         description="تاريخ البداية",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=false,
     *         description="تاريخ النهاية",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="رقم الصفحة",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="عدد العناصر في الصفحة",
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="قائمة طلبات الشحن",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="oto_tracking_number", type="string", example="TRK-789012"),
     *                         @OA\Property(property="status", type="string", example="in_transit"),
     *                         @OA\Property(property="status_label", type="string", example="قيد النقل"),
     *                         @OA\Property(property="receiver_name", type="string", example="أحمد محمد"),
     *                         @OA\Property(property="receiver_city", type="string", example="الرياض"),
     *                         @OA\Property(property="shipping_cost", type="number", format="float", example=35.50),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-10T10:30:00Z")
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="per_page", type="integer", example=20),
     *                 @OA\Property(property="last_page", type="integer", example=5)
     *             )
     *         )
     *     )
     * )
     */
    public function getShippingOrders(Request $request)
    {
        $query = ShippingOrder::with(['order', 'shippingService']);
        
        // الفلترة حسب الحالة
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // الفلترة حسب التاريخ
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // البحث حسب رقم التتبع أو اسم المستلم
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('oto_tracking_number', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('receiver_phone', 'like', "%{$search}%")
                  ->orWhereHas('order', function($q2) use ($search) {
                      $q2->where('order_number', 'like', "%{$search}%");
                  });
            });
        }
        
        // الترتيب
        $query->orderBy('created_at', 'desc');
        
        // التقسيم للصفحات
        $perPage = $request->get('per_page', 20);
        $shippingOrders = $query->paginate($perPage);
        
        // تحويل البيانات
        $transformedData = $shippingOrders->through(function($order) {
            return [
                'id' => $order->id,
                'order_id' => $order->order_id,
                'order_number' => $order->order->order_number ?? null,
                'oto_tracking_number' => $order->oto_tracking_number,
                'status' => $order->status,
                'status_label' => $order->status_label,
                'receiver_name' => $order->receiver_name,
                'receiver_city' => $order->receiver_city,
                'receiver_phone' => $order->receiver_phone,
                'shipping_cost' => $order->shipping_cost,
                'cash_on_delivery_amount' => $order->cash_on_delivery_amount,
                'estimated_delivery' => $order->estimated_delivery_date?->format('Y-m-d'),
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $order->updated_at->format('Y-m-d H:i:s')
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $transformedData,
            'message' => 'تم جلب طلبات الشحن بنجاح'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/shipping/stats",
     *     summary="إحصائيات الشحن",
     *     tags={"Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         required=false,
     *         description="تاريخ البداية",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         required=false,
     *         description="تاريخ النهاية",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="إحصائيات الشحن",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="total_shipments", type="integer", example=150),
     *                 @OA\Property(property="total_revenue", type="number", format="float", example=5250.75),
     *                 @OA\Property(property="total_cod_amount", type="number", format="float", example=75000.00),
     *                 @OA\Property(property="by_status", type="object",
     *                     @OA\Property(property="pending", type="integer", example=10),
     *                     @OA\Property(property="in_transit", type="integer", example=25),
     *                     @OA\Property(property="delivered", type="integer", example=115)
     *                 ),
     *                 @OA\Property(property="by_city", type="object",
     *                     @OA\Property(property="الرياض", type="integer", example=60),
     *                     @OA\Property(property="جدة", type="integer", example=45),
     *                     @OA\Property(property="الدمام", type="integer", example=30)
     *                 ),
     *                 @OA\Property(property="top_services", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="service_name", type="string", example="خدمة سريعة"),
     *                         @OA\Property(property="count", type="integer", example=100)
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getShippingStats(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        $report = $this->otoService->generateShippingReport($startDate, $endDate);
        
        return response()->json([
            'success' => true,
            'data' => $report,
            'message' => 'تم جلب إحصائيات الشحن بنجاح'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/shipping/sync",
     *     summary="مزامنة البيانات مع OTO",
     *     tags={"Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="sync_type", type="string", enum={"cities", "districts", "services", "tracking", "all"}, example="all")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تمت المزامنة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تمت مزامنة البيانات بنجاح"),
     *             @OA\Property(property="results", type="object",
     *                 @OA\Property(property="cities_synced", type="boolean", example=true),
     *                 @OA\Property(property="services_synced", type="boolean", example=true),
     *                 @OA\Property(property="tracking_updated", type="integer", example=25)
     *             )
     *         )
     *     )
     * )
     */
    public function syncWithOto(Request $request)
    {
        try {
            $syncType = $request->get('sync_type', 'all');
            $results = [];
            
            if ($syncType === 'all' || $syncType === 'cities') {
                $results['cities_synced'] = $this->otoService->fetchCities();
            }
            
            if ($syncType === 'all' || $syncType === 'services') {
                $results['services_synced'] = $this->otoService->fetchShippingServices();
            }
            
            if ($syncType === 'all' || $syncType === 'tracking') {
                $results['tracking_updated'] = $this->otoService->updateAllShipmentsTracking();
            }
            
            if ($syncType === 'all' || $syncType === 'weights') {
                $results['weights_updated'] = $this->otoService->updateProductWeightsFromOrders();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'تمت مزامنة البيانات بنجاح',
                'results' => $results
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء المزامنة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إنشاء ملصق بديل
     */
    private function generateAlternativeLabel($shippingOrder)
    {
        // يمكنك استخدام مكتبة مثل Dompdf لإنشاء PDF
        // هذا مثال مبسط
        $html = view('shipping.label', compact('shippingOrder'))->render();
        
        // استخدم مكتبة PDF لتحويل HTML إلى PDF
        // return PDF::loadHTML($html)->stream('shipping_label.pdf');
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="shipping_label_' . $shippingOrder->oto_tracking_number . '.html"');
    }
}
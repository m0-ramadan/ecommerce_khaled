<?php

namespace App\Http\Controllers\Api\Website;

use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Models\ShippingOrder;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Services\OtoV2ShippingService;
use Illuminate\Support\Facades\Validator;

class OtoV2ShippingController extends Controller
{
    protected $otoService;

    public function __construct(OtoV2ShippingService $otoService)
    {
        $this->otoService = $otoService;
    }

    /**
     * @OA\Post(
     *     path="/api/v2/shipping/create",
     *     summary="إنشاء شحنة جديدة",
     *     tags={"OTO V2 Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_number", "receiver_name", "receiver_phone", "receiver_city", "receiver_address"},
     *             @OA\Property(property="order_number", type="string", example="ORDER-2024-00123"),
     *             @OA\Property(property="receiver_name", type="string", example="أحمد محمد"),
     *             @OA\Property(property="receiver_phone", type="string", example="0501234567"),
     *             @OA\Property(property="receiver_email", type="string", example="ahmed@example.com"),
     *             @OA\Property(property="receiver_city", type="string", example="Riyadh"),
     *             @OA\Property(property="receiver_district", type="string", example="Al Sulimaniyah"),
     *             @OA\Property(property="receiver_address", type="string", example="King Fahd Road, Building 123"),
     *             @OA\Property(property="receiver_postal_code", type="string", example="12211"),
     *             @OA\Property(property="short_address_code", type="string", example="ADD-001"),
     *             @OA\Property(property="weight", type="number", format="float", example=2.5),
     *             @OA\Property(property="pieces_count", type="integer", example=1),
     *             @OA\Property(property="declared_value", type="number", format="float", example=150.00),
     *             @OA\Property(property="payment_type", type="string", enum={"cod", "prepaid"}, example="cod"),
     *             @OA\Property(property="cod_amount", type="number", format="float", example=150.00),
     *             @OA\Property(property="who_pays", type="string", enum={"sender", "recipient"}, example="sender"),
     *             @OA\Property(property="delivery_company", type="string", example="aramex"),
     *             @OA\Property(property="service_type", type="string", example="standard"),
     *             @OA\Property(property="pickup_location", type="string", example="WAREHOUSE-001"),
     *             @OA\Property(property="delivery_type", type="string", enum={"delivery", "pickup"}, example="delivery"),
     *             @OA\Property(property="notes", type="string", example="توصيل بعد الساعة 5 مساءً"),
     *             @OA\Property(property="dimensions", type="object",
     *                 @OA\Property(property="length", type="number", format="float", example=30),
     *                 @OA\Property(property="width", type="number", format="float", example=20),
     *                 @OA\Property(property="height", type="number", format="float", example=15),
     *                 @OA\Property(property="unit", type="string", example="cm")
     *             ),
     *             @OA\Property(property="boxes", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="items", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم إنشاء الشحنة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إنشاء الشحنة بنجاح"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="order_id", type="string", example="OTO-20240115-001"),
     *                 @OA\Property(property="tracking_number", type="string", example="TRK-7890123456"),
     *                 @OA\Property(property="label_url", type="string", example="https://api.tryoto.com/labels/TRK-7890123456.pdf"),
     *                 @OA\Property(property="status", type="string", example="new")
     *             )
     *         )
     *     )
     * )
     */
    public function createShipment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|string|max:100',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_email' => 'nullable|email|max:255',
            'receiver_city' => 'required|string|max:100',
            'receiver_district' => 'nullable|string|max:100',
            'receiver_address' => 'required|string|max:500',
            'receiver_postal_code' => 'nullable|string|max:10',
            'weight' => 'required|numeric|min:0.1',
            'pieces_count' => 'required|integer|min:1',
            'declared_value' => 'required|numeric|min:0',
            'payment_type' => 'required|in:cod,prepaid',
            'cod_amount' => 'required_if:payment_type,cod|numeric|min:0',
            'who_pays' => 'required|in:sender,recipient'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صالحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->otoService->createShipment($request->all());

            if ($result['success']) {
                // حفظ الشحنة في قاعدة البيانات
                $shippingOrder = $this->saveShippingOrder($request, $result);
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء الشحنة بنجاح',
                    'data' => [
                        'order_id' => $result['order_id'],
                        'tracking_number' => $result['tracking_number'],
                        'label_url' => $result['label_url'],
                        'status' => 'new',
                        'shipping_order_id' => $shippingOrder->id
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'error' => $result['error'] ?? 'UNKNOWN_ERROR',
                'oto_error' => [
                    'code' => $result['oto_error_code'] ?? null,
                    'message' => $result['oto_error_message'] ?? null
                ]
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v2/shipping/create-from-address/{orderId}/{addressId}",
     *     summary="إنشاء شحنة من عنوان مستخدم",
     *     tags={"OTO V2 Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         description="معرف الطلب",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="addressId",
     *         in="path",
     *         required=true,
     *         description="معرف عنوان المستخدم",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="delivery_company", type="string", example="aramex"),
     *             @OA\Property(property="service_type", type="string", example="standard"),
     *             @OA\Property(property="who_pays", type="string", enum={"sender", "recipient"}, example="sender"),
     *             @OA\Property(property="notes", type="string", example="ملاحظات إضافية")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم إنشاء الشحنة بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إنشاء الشحنة من العنوان بنجاح")
     *         )
     *     )
     * )
     */
    public function createShipmentFromAddress($orderId, $addressId, Request $request)
    {
        try {
            $result = $this->otoService->createShipmentFromUserAddress(
                $orderId,
                $addressId,
                $request->all()
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء الشحنة من العنوان بنجاح',
                    'data' => $result['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'error' => $result['error']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v2/shipping/track/{trackingNumber}",
     *     summary="تتبع الشحنة",
     *     tags={"OTO V2 Shipping"},
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
     *                 @OA\Property(property="tracking_number", type="string", example="TRK-7890123456"),
     *                 @OA\Property(property="status", type="string", example="in_transit"),
     *                 @OA\Property(property="status_label", type="string", example="قيد النقل"),
     *                 @OA\Property(property="tracking_events", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="status", type="string", example="picked_up"),
     *                         @OA\Property(property="description", type="string", example="تم الاستلام"),
     *                         @OA\Property(property="timestamp", type="string", format="date-time", example="2024-01-15T10:30:00Z"),
     *                         @OA\Property(property="location", type="string", example="الرياض")
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
            $result = $this->otoService->trackShipment($trackingNumber);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'error' => $result['error']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v2/shipping/track-driver",
     *     summary="تتبع السائق في الوقت الحقيقي",
     *     tags={"OTO V2 Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_id"},
     *             @OA\Property(property="order_id", type="string", example="OID-9885-70000203806")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="معلومات تتبع السائق",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="driver_name", type="string", example="سائق OTO"),
     *                 @OA\Property(property="current_location", type="object",
     *                     @OA\Property(property="lat", type="number", format="float", example=24.7136),
     *                     @OA\Property(property="lng", type="number", format="float", example=46.6753)
     *                 ),
     *                 @OA\Property(property="last_update", type="string", format="date-time", example="2024-01-15T10:30:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function trackDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صالحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->otoService->trackDriver($request->order_id);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'error' => $result['error']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v2/shipping/calculate",
     *     summary="حساب تكلفة الشحن",
     *     tags={"OTO V2 Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"from_city", "to_city", "weight"},
     *             @OA\Property(property="from_city", type="string", example="Riyadh"),
     *             @OA\Property(property="to_city", type="string", example="Jeddah"),
     *             @OA\Property(property="district", type="string", example="Al Sulimaniyah"),
     *             @OA\Property(property="weight", type="number", format="float", example=2.5),
     *             @OA\Property(property="pieces_count", type="integer", example=1),
     *             @OA\Property(property="declared_value", type="number", format="float", example=150.00),
     *             @OA\Property(property="payment_type", type="string", enum={"cod", "prepaid"}, example="cod"),
     *             @OA\Property(property="cod_amount", type="number", format="float", example=150.00),
     *             @OA\Property(property="dimensions", type="object",
     *                 @OA\Property(property="length", type="number", format="float", example=30),
     *                 @OA\Property(property="width", type="number", format="float", example=20),
     *                 @OA\Property(property="height", type="number", format="float", example=15)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تكلفة الشحن",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="estimated_cost", type="number", format="float", example=35.50),
     *                 @OA\Property(property="estimated_days", type="integer", example=2),
     *                 @OA\Property(property="available_services", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="company", type="string", example="aramex"),
     *                         @OA\Property(property="service", type="string", example="express"),
     *                         @OA\Property(property="cost", type="number", format="float", example=45.00),
     *                         @OA\Property(property="estimated_days", type="integer", example=1)
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function calculateShipping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_city' => 'required|string',
            'to_city' => 'required|string',
            'weight' => 'required|numeric|min:0.1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صالحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->otoService->calculateShipping($request->all());

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'error' => $result['error']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v2/shipping/cities",
     *     summary="الحصول على المدن",
     *     tags={"OTO V2 Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="قائمة المدن",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="string", example="RIY"),
     *                     @OA\Property(property="name_ar", type="string", example="الرياض"),
     *                     @OA\Property(property="name_en", type="string", example="Riyadh"),
     *                     @OA\Property(property="region_ar", type="string", example="منطقة الرياض"),
     *                     @OA\Property(property="is_active", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     )
     * )
     */
public function getCities()
{
    try {
        $result = $this->otoService->getCities();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'total' => $result['total'] ?? count($result['data'])
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'فشل جلب المدن',
            'error' => $result['error'] ?? null
        ], 400);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ غير متوقع',
            'error' => $e->getMessage()
        ], 500);
    }
}



    /**
     * @OA\Get(
     *     path="/api/v2/shipping/cities/{cityCode}/districts",
     *     summary="الحصول على المناطق/الأحياء",
     *     tags={"OTO V2 Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="cityCode",
     *         in="path",
     *         required=true,
     *         description="كود المدينة",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="قائمة المناطق",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="string", example="DIST-001"),
     *                     @OA\Property(property="name_ar", type="string", example="حي السليمانية"),
     *                     @OA\Property(property="name_en", type="string", example="Al Sulimaniyah"),
     *                     @OA\Property(property="postal_code", type="string", example="12211")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getDistricts($cityCode)
    {
        try {
            $result = $this->otoService->getDistricts($cityCode);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'error' => $result['error']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v2/shipping/label/{trackingNumber}",
     *     summary="الحصول على ملصق الشحن",
     *     tags={"OTO V2 Shipping"},
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
     *         description="صيغة الملصق",
     *         @OA\Schema(type="string", enum={"pdf", "png"}, default="pdf")
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
    public function getLabel($trackingNumber, Request $request)
    {
        try {
            $format = $request->get('format', 'pdf');
            $result = $this->otoService->getLabel($trackingNumber, $format);

            if ($result['success']) {
                return response($result['content'])
                    ->header('Content-Type', $result['content_type'])
                    ->header('Content-Disposition', "inline; filename=\"shipping_label_{$trackingNumber}.{$format}\"");
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'error' => $result['error']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v2/shipping/cancel",
     *     summary="إلغاء الشحنة",
     *     tags={"OTO V2 Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tracking_number"},
     *             @OA\Property(property="tracking_number", type="string", example="TRK-7890123456"),
     *             @OA\Property(property="reason", type="string", example="رغبة العميل")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="تم الإلغاء بنجاح",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="تم إلغاء الشحنة بنجاح")
     *         )
     *     )
     * )
     */
    public function cancelShipment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tracking_number' => 'required|string',
            'reason' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صالحة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->otoService->cancelShipment(
                $request->tracking_number,
                $request->reason
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إلغاء الشحنة بنجاح',
                    'data' => $result['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'error' => $result['error']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v2/shipping/sync",
     *     summary="مزامنة جميع الشحنات",
     *     tags={"OTO V2 Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="تمت المزامنة",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="updated_count", type="integer", example=25),
     *             @OA\Property(property="total", type="integer", example=50)
     *         )
     *     )
     * )
     */
    public function syncShipments()
    {
        try {
            $result = $this->otoService->updateAllShipments();

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'تمت مزامنة الشحنات بنجاح',
                    'data' => [
                        'updated_count' => $result['updated_count'],
                        'total' => $result['total']
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'error' => $result['error']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v2/shipping/orders",
     *     summary="الحصول على قائمة الشحنات",
     *     tags={"OTO V2 Shipping"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="حالة الشحنة",
     *         @OA\Schema(type="string")
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
     *         description="قائمة الشحنات",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="order_id", type="string", example="ORDER-2024-001"),
     *                     @OA\Property(property="tracking_number", type="string", example="TRK-7890123456"),
     *                     @OA\Property(property="status", type="string", example="in_transit"),
     *                     @OA\Property(property="receiver_name", type="string", example="أحمد محمد"),
     *                     @OA\Property(property="receiver_city", type="string", example="الرياض"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-15T10:30:00Z")
     *                 )
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="per_page", type="integer", example=20),
     *                 @OA\Property(property="current_page", type="integer", example=1)
     *             )
     *         )
     *     )
     * )
     */
    public function getShippingOrders(Request $request)
    {
        try {
            $query = ShippingOrder::query();
            
            // الفلترة حسب الحالة
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            // البحث
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('oto_order_id', 'like', "%{$search}%")
                      ->orWhere('oto_tracking_number', 'like', "%{$search}%")
                      ->orWhere('receiver_name', 'like', "%{$search}%")
                      ->orWhere('receiver_phone', 'like', "%{$search}%");
                });
            }
            
            // الترتيب
            $query->orderBy('created_at', 'desc');
            
            // التقسيم للصفحات
            $perPage = $request->get('per_page', 20);
            $shippingOrders = $query->paginate($perPage);
            
            // تحويل البيانات
            $data = $shippingOrders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'oto_order_id' => $order->oto_order_id,
                    'tracking_number' => $order->oto_tracking_number,
                    'status' => $order->status,
                    'status_label' => $order->status_label,
                    'receiver_name' => $order->receiver_name,
                    'receiver_city' => $order->receiver_city,
                    'receiver_phone' => $order->receiver_phone,
                    'shipping_cost' => $order->shipping_cost,
                    'cod_amount' => $order->cash_on_delivery_amount,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $order->updated_at->format('Y-m-d H:i:s')
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'total' => $shippingOrders->total(),
                    'per_page' => $shippingOrders->perPage(),
                    'current_page' => $shippingOrders->currentPage(),
                    'last_page' => $shippingOrders->lastPage()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حفظ الشحنة في قاعدة البيانات
     */
    private function saveShippingOrder($request, $apiResult)
    {
        return ShippingOrder::create([
            'order_id' => $request->input('order_id'),
            'oto_order_id' => $apiResult['order_id'] ?? null,
            'oto_tracking_number' => $apiResult['tracking_number'] ?? null,
            'shipping_service_id' => $request->input('service_id', 1),
            'status' => 'new',
            
            // بيانات المرسل
            'sender_name' => config('services.oto.sender_name'),
            'sender_phone' => config('services.oto.sender_phone'),
            'sender_city' => config('services.oto.sender_city'),
            'sender_district' => config('services.oto.sender_district'),
            'sender_address' => config('services.oto.sender_address'),
            
            // بيانات المستلم
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
            'declared_value' => $request->declared_value,
            'content_description' => $request->notes ?? 'منتجات متجر',
            
            // معلومات الدفع
            'payment_type' => $request->payment_type,
            'cash_on_delivery_amount' => $request->cod_amount ?? 0,
            'shipping_cost' => 0, // سيتم تحديثها لاحقاً
            'total_amount' => ($request->cod_amount ?? 0),
            
            // معلومات OTO
            'oto_response' => $apiResult,
            'notes' => $request->notes
        ]);
    }
}
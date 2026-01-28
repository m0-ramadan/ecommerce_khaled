<?php

namespace App\Http\Controllers\Api\Website;
use App\Models\Order;

use App\Models\Shipment;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\OtoShippingService;

class ShippingController extends Controller
{
    private $shippingService;

    public function __construct(OtoShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    /**
     * إنشاء شحنة جديدة
     */
    public function createShipment(Request $request, Order $order)
    {
        $request->validate([
            'delivery_option_id' => 'required|string',
        ]);

        $shipment = $this->shippingService->createShipment($order, $request->all());

        if ($shipment) {
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الشحنة بنجاح',
                'data' => $shipment
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'فشل في إنشاء الشحنة'
        ], 500);
    }

    /**
     * الحصول على تكلفة التوصيل
     */
    public function checkDeliveryFee(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric',
            'originCity' => 'required|string',
            'destinationCity' => 'required|string',
            'height' => 'required|numeric',
            'width' => 'required|numeric',
            'length' => 'required|numeric',
        ]);

        $fee = $this->shippingService->checkDeliveryFee($request->all());

        return response()->json($fee);
    }

    /**
     * تحديث حالة الشحنة
     */
    public function updateShipmentStatus(Shipment $shipment)
    {
        $updated = $this->shippingService->updateShipmentStatus($shipment);

        return response()->json([
            'success' => $updated,
            'message' => $updated ? 'تم تحديث الحالة' : 'فشل في تحديث الحالة'
        ]);
    }

    /**
     * الحصول على خيارات التوصيل
     */
    public function getDeliveryOptions()
    {
        $options = $this->shippingService->getDeliveryOptions();

        return response()->json($options);
    }
}
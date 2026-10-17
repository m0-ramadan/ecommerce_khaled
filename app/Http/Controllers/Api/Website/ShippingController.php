<?php

namespace App\Http\Controllers\Api\Website;

use App\Models\Order;

use App\Models\Shipment;
use Illuminate\Http\Request;

use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\OtoShippingService;
use Illuminate\Support\Facades\Auth;

class ShippingController extends Controller
{
    use ApiResponseTrait;
    private $shippingService;

    public function __construct(OtoShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }
    /**
     * الحصول على خيارات التوصيل بناء على العنوان المختار والمدينة
     */
    public function getDeliveryOptions(Request $request)
    {
        try {
            $addressId = $request->input('address_id');
            $cityName = $request->input('city');

            $options = $this->shippingService->getDeliveryOptions($addressId, $cityName);

            return $this->success([
                'deliveryFees' => $options['deliveryFees'],
                'orderId'      => $options['orderId'],
            ]);
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 400);
        } catch (\Throwable $e) {
            Log::error('Delivery options failed', [
                'error' => $e->getMessage()
            ]);

            return $this->error('تعذر جلب شركات الشحن المتاحة حاليًا. يرجى المحاولة مرة أخرى.');
        }
    }
}

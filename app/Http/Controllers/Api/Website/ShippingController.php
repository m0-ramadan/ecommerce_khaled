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
     * الحصول على خيارات التوصيل
     */
    public function getDeliveryOptions()
    {
        try {
            $options = $this->shippingService->getDeliveryOptions();

            return $this->success([
                'deliveryFees' => $options['deliveryFees'],
                'orderId'      => $options['orderId'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Delivery options failed', [
                'error' => $e->getMessage()
            ]);

            return $this->error('Failed to get delivery options' . $e->getMessage());
        }
    }
}

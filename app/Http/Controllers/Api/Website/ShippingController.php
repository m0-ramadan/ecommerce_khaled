<?php

namespace App\Http\Controllers\Api\Website;
use App\Models\Order;

use App\Models\Shipment;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\OtoShippingService;
use App\Traits\ApiResponseTrait;

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

        $options = $this->shippingService->getDeliveryOptions();

        return $this->success($options);
    }
}
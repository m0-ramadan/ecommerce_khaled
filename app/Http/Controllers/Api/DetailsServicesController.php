<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailsResource;
use App\Http\Resources\ServiceResource;
use App\Models\Detail;
use App\Models\Service;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class DetailsServicesController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $details = DetailsResource::collection(Detail::get());
        $services = ServiceResource::collection(Service::get());

        return $this->apiResponse(data: [
            'details' => $details,
            'services' => $services
        ]);
    }
}

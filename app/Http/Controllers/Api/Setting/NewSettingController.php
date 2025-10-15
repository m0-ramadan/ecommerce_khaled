<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewSettingResource;
use App\Models\NewSetting;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class NewSettingController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $settings = NewSettingResource::collection(NewSetting::get(['name', 'value', 'title']));
        return $this->apiResponse(data: $settings);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Traits\TranslatableTrait;
use Illuminate\Http\Request;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DeviceTokenController extends Controller
{
    use TranslatableTrait;

    public function store(Request $request)
    {
        try {
            $request->validate([
                'device_token' => 'required|string',
            ]);

            DeviceToken::create([
                'model_type' => Auth::guard('sanctum')->check() ? get_class(Auth::guard('sanctum')->user()) : null,
                'model_id' => Auth::guard('sanctum')->user()->id ?? null,
                'device_token' => $request->device_token,
            ]);

            return response()->json([
                'status' => true,
                'message' => $this->translate('device_token_saved'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $this->translate('failed_to_save_device_token'),
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
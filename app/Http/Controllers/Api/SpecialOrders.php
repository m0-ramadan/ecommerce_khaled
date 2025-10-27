<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\TranslatableTrait;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentTypeResource;
use App\Http\Resources\CountryResource;
use App\Models\PaymentType;
use App\Models\Country;
use App\Models\ShipmentCompany;
use Illuminate\Support\Facades\Auth;
use App\Models\Shipment;
use App\Http\Resources\ShipmentCompanyResource;

class SpecialOrders extends Controller
{
    use TranslatableTrait; // استخدام الـ Trait للترجمة

    public function dataShipment()
    {
        $paymentTypes = PaymentType::all();
        $countries = Country::with('region')->get(); // تحميل العلاقة 'region'

        return response()->json([
            'status' => true,
            'message' => $this->translate('data_retrieved_successfully'), // ترجمة الرسالة
            'data' => [
                'payments' => PaymentTypeResource::collection($paymentTypes),
                'countries' => CountryResource::collection($countries),
            ],
        ], 200);
    }

    public function booking(Request $request)
    {
        try {
            $validated = $request->validate([
                'sender_name' => 'required|string|max:255',
                'sender_address' => 'nullable|string',
                'sender_phone' => 'required|max:255',
                'sender_region' => 'required|max:255',
                'sender_country' => 'required|max:255',
                'size' => 'nullable|integer',
                'weight' => 'nullable|integer',
                'image' => 'nullable|string|max:255',
                'service_id' => 'nullable|integer',
                'payment_type_id' => 'nullable|integer',
                'shipment_company_id' => 'nullable|integer',
                'price' => 'nullable|integer',
                'details' => 'nullable|string',
            ]);

            $validated['user_id'] = Auth::id();
            $validated['code'] = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);

            $shipment = Shipment::create($validated);

            return response()->json([
                'message' => $this->translate('shipment_created_successfully'), // ترجمة الرسالة
                'data' => $shipment
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => $this->translate('validation_failed'), // ترجمة الرسالة
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->translate('error_processing_request'), // ترجمة الرسالة
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
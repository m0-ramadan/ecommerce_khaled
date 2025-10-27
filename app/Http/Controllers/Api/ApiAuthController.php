<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\otp;
use App\Models\User;
use App\Models\Region;
use App\Models\Branchs;
use App\Models\Clients;
use App\Models\Country;
use App\Mail\ResetPassword;

use App\Models\DeviceToken;
use Illuminate\Support\Str;
use App\Models\WalletDetail;
use Illuminate\Http\Request;
use App\Traits\SyncClientToErp;
use App\Models\dashboard\Client;
use App\Traits\TranslatableTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\CitiesResource;
use App\Http\Resources\RegionResource;
use App\Http\Resources\CountryResource;
use App\Notifications\NotificationClient;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    use TranslatableTrait, SyncClientToErp;

    public function __construct()
    {
        $locale = request()->header('lang', 'ar');
        App::setLocale($locale);
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:clients',
                'phone' => 'required|string|unique:clients',
                'phone2' => 'nullable',
                'country_id' => 'required|exists:countries,id',
                'region_id' => 'nullable',
                'branch_id' => 'nullable',
                'address' => 'nullable|string',
                'password' => 'required|min:6',
                'type' => 'required',
                'birth_date' => 'nullable',
                'company_representative_name' => 'nullable',
                'device_token' => 'nullable|string',
                'representative_phone' => 'nullable',
                'representative_email' => 'nullable',
            ], [
                'name.required' => 'الاسم مطلوب',
                'name.string' => 'الاسم يجب أن يكون نصًا',
                'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرفًا',
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'يجب إدخال بريد إلكتروني صالح',
                'email.max' => 'البريد الإلكتروني لا يمكن أن يتجاوز 255 حرفًا',
                'email.unique' => 'البريد الإلكتروني مسجل مسبقًا',
                'phone.required' => 'رقم الهاتف مطلوب',
                'phone.unique' => 'رقم الهاتف مسجل مسبقًا',
                'country_id.required' => 'معرف الدولة مطلوب',
                'country_id.exists' => 'الدولة المختارة غير موجودة',
                'password.required' => 'كلمة المرور مطلوبة',
                'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
                'type.required' => 'النوع مطلوب',
            ]);

            $count = Clients::where('type', $validatedData['type'])->count();
            $newCodeNumber = $count + 1;

            $client = Clients::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'phone2' => $validatedData['phone2'] ?? null,
                'country_id' => $validatedData['country_id'],
                'region_id' => $validatedData['region_id'],
                'address' => $validatedData['address'] ?? null,
                'birth_date' => $validatedData['birth_date'] ?? null,
                'type' => $validatedData['type'],
                'branch_id' => $validatedData['branch_id'] ?? null,
                'status' => 0,
                'company_representative_name' => $validatedData['company_representative_name'] ?? null,
                'representative_phone' => $validatedData['representative_phone'] ?? null,
                'representative_email' => $validatedData['representative_email'] ?? null,
                'password' => Hash::make($validatedData['password']),
                'code' => (string) $newCodeNumber,
            ]);

            $this->syncClient($client);

            if (!empty($validatedData['country_id'])) {
                WalletDetail::create([
                    'model_type' => get_class($client),
                    'model_id' => $client->id,
                    'currency_id' => Country::find($validatedData['country_id'])->currency_id,
                ]);
            }

            if (!empty($validatedData['device_token'])) {
                DeviceToken::create([
                    'model_type' => get_class($client),
                    'model_id' => $client->id,
                    'device_token' => $validatedData['device_token'],
                ]);
            }

            $token = $client->createToken(Str::random(40))->plainTextToken;

            return response()->json([
                'message' => $this->translate('register_successful'),
                'client' => $client,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->translate('error_occurred', ['error' => $e->getMessage()]),
            ], 500);
        }
    }
    public function deleteByPhone(Request $request): JsonResponse
    {
        $client = Clients::where('phone', $request->phone)->first();

        if ($client == null) {
            return response()->json(['message' => $this->translate('client_not_found')], 404);
        }

        $client->delete();

        return response()->json(['message' => $this->translate('account_deleted')], 200);
    }

    public function registerget(Request $request)
    {
        // $places = Region::all();

        $cityIds = Branchs::distinct()->pluck('region_id');
        $cities = Region::whereIn('id', $cityIds)->get();

        $countries = Country::all();
        return response()->json([
            'message' => $this->translate('register_data_retrieved'),
            'data' => [
                // 'regions' => RegionResource::collection($places),
                // 'cities' => CitiesResource::collection($cities),
                'countries' => CountryResource::collection($countries)
            ]
        ], 200);
    }

    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'identifier' => 'required|string',
                'password' => 'required|string|min:6',
                'device_token' => 'nullable|string',
            ]);

            Log::info('Login attempt', ['identifier' => $validatedData['identifier']]);

            $client = filter_var($validatedData['identifier'], FILTER_VALIDATE_EMAIL)
                ? Clients::where('email', $validatedData['identifier'])->first()
                : Clients::where('phone', $validatedData['identifier'])->first();

            if (!$client) {
                Log::warning('Client not found', ['identifier' => $validatedData['identifier']]);
                return response()->json(['message' => $this->translate('client_not_found')], 404);
            }
            if (!$client) {
                Log::warning('Client not found', ['identifier' => $validatedData['identifier']]);
                return response()->json(['message' => $this->translate('client_not_found')], 404);
            }

            if (!$client->status) {
                Log::info('Client account not active', ['client_id' => $client->id, 'email' => $client->email]);
                return response()->json(['message' => $this->translate('client_email_not_verified')], 403);
            }

            if (!Hash::check($validatedData['password'], $client->password)) {
                Log::warning('Invalid credentials', ['client_id' => $client->id]);
                return response()->json(['message' => $this->translate('invalid_credentials')], 401);
            }

            // Check status
            if ($client->status != 1) {
                return response()->json(['message' => $this->translate('client_notactive')], 403);
            }

            // Save device token
            if ($validatedData['device_token']) {
                DeviceToken::updateOrCreate(
                    ['model_type' => get_class($client), 'model_id' => $client->id],
                    ['device_token' => $validatedData['device_token']]
                );
            }

            $token = $client->createToken('ClientAppToken')->plainTextToken;

            return response()->json([
                'message' => $this->translate('logged_in_successfully'),
                'client' => $client,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Login error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => $this->translate('error_occurred', ['error' => $e->getMessage()]),
            ], 500);
        }
    }


    // public function logout(Request $request)
    // {
    //     try {
    //         $client = $request->user();


    //         if (!$client) {
    //             return response()->json(['message' => $this->translate('client_not_found')], 404);
    //         }

    //         $client->currentAccessToken()->delete();

    //         return response()->json([
    //             'message' => $this->translate('logged_out_successfully'),
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => $this->translate('error_occurred', ['error' => $e->getMessage()]),
    //         ], 500);
    //     }
    // }
    public function logout(Request $request)
    {
        try {
            $client = $request->user();

            if (!$client) {
                return response()->json(['message' => $this->translate('client_not_found')], 404);
            }

            $client->currentAccessToken()->delete();

            \App\Models\DeviceToken::where('model_type', get_class($client))
                ->where('model_id', $client->id)
                ->delete();

            return response()->json([
                'message' => $this->translate('logged_out_successfully'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->translate('error_occurred', ['error' => $e->getMessage()]),
            ], 500);
        }
    }
    public function changePassword(Request $request)
    {
        // ملاحظة: هذه الدالة غير مكتملة في الكود الأصلي، سأتركها كما هي مع ترجمة الرسالة فقط
        return response()->json([
            'message' => $this->translate('password_changed_successfully'),
        ], 200);
    }

    public function deleteAccount(Request $request)
    {
        try {
            $client = auth()->user();

            if (!$client) {
                return response()->json(['message' => $this->translate('not_authenticated')], 401);
            }
            $this->deleteErpClient($client->id);
            $client->delete();

            return response()->json([
                'message' => $this->translate('account_deleted'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->translate('error_occurred', ['error' => $e->getMessage()]),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $client = auth()->user();
            $validatedData = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:clients,email,' . $client->id,
                'phone' => 'nullable|string|unique:clients,phone,' . $client->id,
                'country_id' => 'nullable|exists:countries,id',
                'region_id' => 'nullable|exists:regions,id',
                'address' => 'nullable|string',
                'password' => 'nullable|min:6',
                'type' => 'nullable',
                'birth_date' => 'nullable|date',
                'company_representative_name' => 'nullable|string',
            ]);

            $client->update([
                'name' => $validatedData['name'] ?? $client->name,
                'email' => $validatedData['email'] ?? $client->email,
                'phone' => $validatedData['phone'] ?? $client->phone,
                'country_id' => $validatedData['country_id'] ?? $client->country_id,
                'region_id' => $validatedData['region_id'] ?? $client->region_id,
                'address' => $validatedData['address'] ?? $client->address,
                'birth_date' => $validatedData['birth_date'] ?? $client->birth_date,
                'type' => $validatedData['type'] ?? $client->type,
                'company_representative_name' => $validatedData['company_representative_name'] ?? $client->company_representative_name,
                'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : $client->password,
            ]);

            return response()->json([
                'message' => $this->translate('profile_updated'),
                'client' => $client,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $this->translate('error_occurred', ['error' => $e->getMessage()]),
            ], 500);
        }
    }

    public function sendOtpByEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:clients,email',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $this->translate('invalid_email')], 400);
            }

            $client = Clients::where('email', $request->email)->first();

            if ($client) {
                $otp = rand(100000, 999999);
                $client->otp = $otp;
                $client->otp_expires_at = now()->addMinutes(155);
                $client->save();

                Mail::to($client->email)->send(new ResetPassword($client));


                return response()->json(['message' => $this->translate('otp_sent'), 'otp' => $otp], 200);
            } else {
                return response()->json(['message' => $this->translate('email_not_registered')], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $this->translate('error_occurred', ['error' => $e->getMessage()])], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:clients,email',
                'otp' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $this->translate('invalid_input'),
                    'errors' => $validator->errors(),
                ], 422);
            }

            $client = Clients::where('email', $request->email)->first();

            if ($client->otp == $request->otp && $client->otp_expires_at > now()) {
                $client->otp = null;
                $client->otp_expires_at = null;
                $client->save();

                return response()->json(['message' => $this->translate('otp_verified')], 200);
            } else {
                return response()->json(['message' => $this->translate('invalid_otp')], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $this->translate('error_occurred', ['error' => $e->getMessage()])], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:clients,email',
                'password' => 'required|confirmed|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => $this->translate('validation_failed'),
                    'errors' => $validator->errors(),
                ], 422);
            }

            $client = Clients::where('email', $request->email)->first();

            $client->password = Hash::make($request->password);
            $client->otp = null;
            $client->otp_expires_at = null;
            $client->save();

            return response()->json(['message' => $this->translate('password_reset')], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $this->translate('error_occurred', ['error' => $e->getMessage()])], 500);
        }
    }
}

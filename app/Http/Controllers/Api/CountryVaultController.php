<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Setting;
use App\Models\Branchs;
use App\Models\Transfer;

use App\Http\Resources\BranchResource;
use App\Http\Resources\CountryNative;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Response;
use App\Http\Resources\CountryRegionResource;
use App\Http\Resources\TransferResource;
use App\Http\Resources\WalletDetailResource;


class CountryVaultController extends Controller
{
      public function index(Request $request)
    {
        try {
            // Get the authenticated user
            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => Response::HTTP_UNAUTHORIZED,
                    'data' => null,
                    'message' => __('messages.unauthorized'),
                ], Response::HTTP_UNAUTHORIZED);
            }
    
            if (!$user->branch || !$user->branch->country) {
                return response()->json([
                    'status' => false,
                    'status_code' => Response::HTTP_BAD_REQUEST,
                    'data' => null,
                    'message' => __('messages.user has no associated branch or country'),
                ], Response::HTTP_BAD_REQUEST);
            }
    
            $countries = Country::with([
                'regions.branches.vaults'
            ])->get();
    
            $setting = Setting::first();
            $commission = $setting ? $setting->commission : null;
    
            $countryResources = CountryResource::collection($countries);
    
            $countries2 = Country::where('id', $user->branch->country->id)->first();
            $branch_vaults_auth=Branchs::where('id',auth()->user()->branch_id)->with('vaults')->first();
            return response()->json([
                'status' => true,
                'status_code' => Response::HTTP_OK,
                'data' => [
                    'branches' => $countryResources,
                    'country_auth' =>new CountryNative($countries2) ,
                    'branch_vaults_auth'=>new BranchResource($branch_vaults_auth),
                    'commission' => $commission,
                    'offices' =>TransferResource::collection(Transfer::where('type',4)->get()) ,
                    'office_commission' =>$user->commission??0 ,
                    'wallets'=>$user->wallets?WalletDetailResource::Collection($user->wallets):null
                    
                ],
                'message' => __('messages.done successfully'),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'status' => false,
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'data' => null,
                'message' => __('messages.error occurred'),
                'error' => app()->environment('local') ? $e->getMessage() : null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function branch(Request $request)
    {
        try {
            $countries = Country::with([
                'regions.branches.vaults' 
            ])->get();

            // إرجاع استجابة ناجحة
            return response()->json([
                'status' => true,
                'status_code' => Response::HTTP_OK,
                'data' => CountryResource::collection($countries),
                'message' => __('messages.done successfully'),
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            // التعامل مع الأخطاء
            return response()->json([
                'status' => false,
                'status_code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'data' => null,
                'message' => __('messages.error occurred'),
                'error' => app()->environment('local') ? $e->getMessage() : null,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

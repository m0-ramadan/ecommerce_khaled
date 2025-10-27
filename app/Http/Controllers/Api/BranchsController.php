<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\TranslatableTrait;
use Illuminate\Http\Request;
use App\Models\Branchs;
use App\Models\Country;
use App\Models\Region;
use App\Models\BranchssPricing;
use Illuminate\Support\Facades\Auth;
class BranchsController extends Controller
{
    use TranslatableTrait;

    public function index()
    {
        $branches = Branchs::with('region')->get();
        return response()->json([
            'status' => 'success',
            'message' => $this->translate('branches_retrieved'),
            'data' => $branches,
        ], 200);
    }

    // public function branchesPrice()
    // {
    //          $user = auth()->user();
             
    //          $city_id=$user->region_id;
             
    //     $countries = Country::where('status', 1)->with(['regions.branches'])->get();

    //     if ($countries->isEmpty()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $this->translate('no_countries_found'),
    //         ], 404);
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => $this->translate('branches_price_retrieved'),
    //         'countries' => $countries->map(function ($country) {
    //             $branches = $country->regions->flatMap(function ($region) {
    //                 return $region->branches;
    //             });

    //             return [
    //                 'id' => $country->id,
    //                 'country' => $country->country,
    //                 'country_ar' => $country->country_ar,
    //                 'status' => $country->status,
    //                 'item_order' => $country->item_order,
    //                 'branches' => $branches->map(function ($branch) {
    //                     return [
    //                         'id' => $branch->id,
    //                         'name' => $branch->name,
    //                         'price' => $branch->price,
    //                         'key' => $branch->key,
    //                     ];
    //                 }),
    //             ];
    //         }),
    //     ], 200);
    // }
    
    
public function branchesPrice(Request $request)
{
    $user = auth()->user();
    $city_id = $user->region_id;
    $branches_price = BranchssPricing::where('city_id_from', $city_id)->with('city')->get();

    $data = $branches_price->map(function ($item) {
        return [
            'id'        => $item->id,
            'price'     => $item->price,
            'currency'  => $item->currency,
            'city_to'   => optional($item->city)->region_ar ,
            'city_from' => optional($item->city_from)->region_ar,
        ];
    });

    return response()->json([
        'status' => 'success',
        'message' => $this->translate('branches_price_retrieved'),
        'data' => $data,
    ], 200);
}

    
}
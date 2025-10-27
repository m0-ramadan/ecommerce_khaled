<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'region',
        'region_ar',
        'status',
        'item_order',
        'price',
        'key'

    ];

    /**
     * Get the country that owns the region.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function branches()
    {
        return $this->hasMany(Branchs::class, 'region_id');
    }
    public function branchesPrice()
    {
        $countries = Country::with(['regions.branches'])->get();

        if ($countries->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No countries found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'countries' => $countries->map(function ($country) {
                $branches = $country->regions->flatMap(function ($region) {
                    return $region->branches;
                });

                return [
                    'id' => $country->id,
                    'country' => $country->country,
                    'country_ar' => $country->country_ar,
                    'status' => $country->status,
                    'item_order' => $country->item_order,
                    'branches' => $branches->map(function ($branch) {
                        return [
                            'id' => $branch->id,
                            'name' => $branch->name,
                            'region_id' => $branch->region_id,
                        ];
                    }),
                ];
            }),
        ], 200);
    }
}
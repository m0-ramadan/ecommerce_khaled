<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaudiCity extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'region_ar',
        'region_en',
        'latitude',
        'longitude',
        'oto_city_code',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7'
    ];

    // العلاقات
    public function districts()
    {
        return $this->hasMany(District::class, 'city_id');
    }

    public function shippingFromPrices()
    {
        return $this->hasMany(ShippingPrice::class, 'from_city_id');
    }

    public function shippingToPrices()
    {
        return $this->hasMany(ShippingPrice::class, 'to_city_id');
    }

    // النطاقات (Scopes)
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region_ar', $region)->orWhere('region_en', $region);
    }

    // Accessors
    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : ($this->name_en ?? $this->name_ar);
    }

    public function getRegionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->region_ar : ($this->region_en ?? $this->region_ar);
    }
}
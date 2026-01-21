<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'city_id',
        'name_ar',
        'name_en',
        'oto_district_code',
        'postal_code',
        'additional_code',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // العلاقات
    public function city()
    {
        return $this->belongsTo(SaudiCity::class, 'city_id');
    }

    // النطاقات
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    // Accessors
    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : ($this->name_en ?? $this->name_ar);
    }

    public function getFullAddressAttribute()
    {
        $cityName = $this->city ? $this->city->name : '';
        return $this->name . '، ' . $cityName;
    }
}
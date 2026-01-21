<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingService extends Model
{
    protected $fillable = [
        'code',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'base_price',
        'is_active',
        'features',
        'delivery_days'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'features' => 'array',
        'base_price' => 'decimal:2'
    ];

    // العلاقات
    public function shippingOrders()
    {
        return $this->hasMany(ShippingOrder::class);
    }

    public function shippingPrices()
    {
        return $this->hasMany(ShippingPrice::class);
    }

    // النطاقات
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    // Accessors
    public function getNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : ($this->name_en ?? $this->name_ar);
    }

    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : ($this->description_en ?? $this->description_ar);
    }
}
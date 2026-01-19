<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingPrice extends Model
{
    protected $fillable = [
        'from_city_id',
        'to_city_id',
        'shipping_service_id',
        'base_price',
        'per_kg_price',
        'cod_percentage',
        'insurance_percentage',
        'estimated_days',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
        'per_kg_price' => 'decimal:2',
        'cod_percentage' => 'decimal:2',
        'insurance_percentage' => 'decimal:2'
    ];

    // العلاقات
    public function fromCity()
    {
        return $this->belongsTo(SaudiCity::class, 'from_city_id');
    }

    public function toCity()
    {
        return $this->belongsTo(SaudiCity::class, 'to_city_id');
    }

    public function shippingService()
    {
        return $this->belongsTo(ShippingService::class);
    }

    // النطاقات
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCities($query, $fromCityId, $toCityId)
    {
        return $query->where('from_city_id', $fromCityId)
                    ->where('to_city_id', $toCityId);
    }

    public function scopeByService($query, $serviceId)
    {
        return $query->where('shipping_service_id', $serviceId);
    }

    // دوال مساعدة
    public function calculateShippingCost($weight, $codAmount = 0, $declaredValue = 0)
    {
        $cost = $this->base_price;
        
        // حساب الوزن الإضافي
        if ($weight > 1) {
            $extraWeight = ceil($weight - 1);
            $cost += $extraWeight * $this->per_kg_price;
        }
        
        // حساب عمولة الدفع عند الاستلام
        if ($codAmount > 0 && $this->cod_percentage > 0) {
            $cost += ($codAmount * $this->cod_percentage / 100);
        }
        
        // حساب التأمين
        if ($declaredValue > 0 && $this->insurance_percentage > 0) {
            $cost += ($declaredValue * $this->insurance_percentage / 100);
        }
        
        return round($cost, 2);
    }
}
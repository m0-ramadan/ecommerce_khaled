<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingTiers extends Model
{
    // 15.  إنشاء جدول pricing_tiers (التسعير حسب الكمية)

    use HasFactory;
    protected $fillable = [
        'price_per_unit',
        'quantity',
        'is_sample',
        'product_id',
        'discount_percentage',
    ];
    public $table = 'pricing_tiers';
}

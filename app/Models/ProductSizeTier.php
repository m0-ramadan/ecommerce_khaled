<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSizeTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'option_id',
        'related_option_id',
        'quantity',
        'tier_name',
        'price_per_unit',
        'total_price',
        'is_quantity_tier',
        'tier_type',
        'dependency_conditions'
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
        'is_quantity_tier' => 'boolean',
        'dependency_conditions' => 'array'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function option()
    {
        return $this->belongsTo(ProductOptions::class, 'option_id');
    }

    public function relatedOption()
    {
        return $this->belongsTo(ProductOptions::class, 'related_option_id');
    }
}

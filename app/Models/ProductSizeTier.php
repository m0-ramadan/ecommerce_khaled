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
    
    // Scopes
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
    
    public function scopeForOption($query, $optionId)
    {
        return $query->where('option_id', $optionId);
    }
    
    public function scopeQuantityTiers($query)
    {
        return $query->where('is_quantity_tier', true);
    }
    
    public function scopeWithDependency($query, $optionId, $detailId = null)
    {
        return $query->where(function($q) use ($optionId, $detailId) {
            $q->whereNull('dependency_conditions')
              ->orWhereJsonContains('dependency_conditions->depends_on_option_id', $optionId);
            
            if ($detailId) {
                $q->whereJsonContains('dependency_conditions->depends_on_detail_id', $detailId);
            }
        });
    }
    
    // Helper Methods
    public function isAvailableForSelection($selectedOptions = [])
    {
        if (empty($this->dependency_conditions)) {
            return true;
        }
        
        foreach ($this->dependency_conditions as $condition) {
            $optionId = $condition['depends_on_option_id'] ?? null;
            $detailId = $condition['depends_on_detail_id'] ?? null;
            $expectedValue = $condition['value'] ?? null;
            
            if (!$optionId || !isset($selectedOptions[$optionId])) {
                return false;
            }
            
            if ($detailId && $selectedOptions[$optionId] !== $detailId) {
                return false;
            }
            
            if ($expectedValue && $selectedOptions[$optionId] !== $expectedValue) {
                return false;
            }
        }
        
        return true;
    }
    
    public function getFormattedPrice()
    {
        return number_format($this->price_per_unit, 2) . ' ر.س';
    }
    
    public function getFormattedTotal()
    {
        $total = $this->total_price ?? ($this->quantity * $this->price_per_unit);
        return number_format($total, 2) . ' ر.س';
    }
}
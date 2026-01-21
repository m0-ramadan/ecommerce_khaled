<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptions extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'external_option_id',
        'external_detail_id',
        'option_name',
        'option_value',
        'additional_price',
        'is_required',
        'depends_on_option_id',
        'depends_on_detail_id',
        'dependency_condition'
    ];
    
    public $table = 'product_options';
    
    // Casts
    protected $casts = [
        'is_required' => 'boolean',
        'additional_price' => 'decimal:2'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

 

    public function parentDetail()
    {
        return $this->belongsTo(ProductOptions::class, 'depends_on_detail_id');
    }



    public function quantityTiers()
    {
        return $this->hasMany(ProductSizeTier::class, 'option_id');
    }

    public function relatedTiers()
    {
        return $this->hasMany(ProductSizeTier::class, 'related_option_id');
    }
    
    // Scopes
    public function scopeMainOptions($query)
    {
        return $query->whereNull('depends_on_option_id');
    }
    
    public function scopeDependentOptions($query, $optionId = null)
    {
        $query = $query->whereNotNull('depends_on_option_id');
        
        if ($optionId) {
            $query->where('depends_on_option_id', $optionId);
        }
        
        return $query;
    }
    
    public function scopeQuantityOptions($query)
    {
        return $query->where('option_name', 'like', '%كمية%')
                    ->orWhere('option_name', 'like', '%عدد%')
                    ->orWhere('option_name', 'like', '%حبات%');
    }
    
    // Helper Methods
    public function hasDependencies()
    {
        return !is_null($this->depends_on_option_id);
    }
    
    public function getDependencyChain()
    {
        $chain = [];
        $current = $this;
        
        while ($current->depends_on_option_id) {
            $parent = $current->parentOption;
            if ($parent) {
                $chain[] = [
                    'option' => $parent->option_name,
                    'value' => $parent->option_value,
                    'condition' => $current->dependency_condition
                ];
                $current = $parent;
            } else {
                break;
            }
        }
        
        return array_reverse($chain);
    }
    

    // في ProductOptions model
public function parentOption()
{
    return $this->belongsTo(ProductOptions::class, 'depends_on_option_id');
}

public function dependentOptions()
{
    return $this->hasMany(ProductOptions::class, 'depends_on_option_id');
}

public function getAvailableQuantityTiers()
{
    return ProductSizeTier::where('option_id', $this->id)
        ->where('is_quantity_tier', true)
        ->orderBy('quantity')
        ->get();
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptions extends Model
{
    protected $table = 'product_options';

    protected $fillable = [
        'product_id',
        'external_option_id',
        'external_detail_id',
        'option_name',
        'option_value',
        'additional_price',
        'is_required',
        'category',
        'extra_data',
        'depends_on_option_id',
        'depends_on_detail_id',
        'dependency_condition',
        'dependency_operator',
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
        'is_required' => 'boolean',
        'extra_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function dependsOn()
    {
        return $this->belongsTo(ProductOptions::class, 'depends_on_option_id');
    }

    public function dependents()
    {
        return $this->hasMany(ProductOptions::class, 'depends_on_option_id');
    }

    // Scopes
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeIndependent($query)
    {
        return $query->whereNull('depends_on_option_id');
    }

    public function scopeDependent($query)
    {
        return $query->whereNotNull('depends_on_option_id');
    }

    public function scopeWithPrice($query)
    {
        return $query->where('additional_price', '>', 0);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return number_format($this->additional_price, 2) . ' SAR';
    }

    public function getHasDependencyAttribute()
    {
        return !is_null($this->depends_on_option_id);
    }

    public function getExtraDataArrayAttribute()
    {
        return is_array($this->extra_data) ? $this->extra_data : [];
    }


    // Helpers
    public function getSizeTiers()
    {
        $extra = $this->extra_data_array;
        return $extra['size_tiers'] ?? [];
    }

    public function getHexCode()
    {
        $extra = $this->extra_data_array;
        return $extra['hex_code'] ?? null;
    }

    public function getDeliveryDays()
    {
        $extra = $this->extra_data_array;
        return [
            'from' => $extra['from_days'] ?? null,
            'to' => $extra['to_days'] ?? null
        ];
    }

    public function getWeight()
    {
        $extra = $this->extra_data_array;
        return $extra['weight'] ?? null;
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


    public function dependentOptions()
    {
        return $this->hasMany(
            self::class,
            'depends_on_option_id'
        );
    }


    public function parentOption()
    {
        return $this->belongsTo(
            self::class,
            'depends_on_option_id',
            'id'
        );
    }
    public function getAvailableQuantityTiers()
    {
        return ProductSizeTier::where('option_id', $this->id)
            ->where('is_quantity_tier', true)
            ->orderBy('quantity')
            ->get();
    }
}

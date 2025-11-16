<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'has_discount',
        'includes_tax',
        'includes_shipping',
        'stock',
        'status_id','image',
    ];

    protected $casts = [
        'has_discount' => 'boolean',
        'includes_tax' => 'boolean',
        'includes_shipping' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function discount()
    {
        return $this->hasOne(Discount::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    public function deliveryTime()
    {
        return $this->hasOne(DeliveryTime::class);
    }

    public function warranty()
    {
        return $this->hasOne(Warranty::class);
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }

    public function getFinalPriceAttribute()
    {
        if ($this->has_discount && $this->discount) {
            if ($this->discount->discount_type === 'percentage') {
                return $this->price - ($this->price * $this->discount->discount_value / 100);
            }
            return $this->price - $this->discount->discount_value;
        }
        return $this->price;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
    public function favouritedBy()
    {
        return $this->belongsToMany(\App\Models\User::class, 'favourites');
    }
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'material_product')
            ->withPivot(['quantity', 'unit'])
            ->withTimestamps();
    }

    // =================================================================
    // Scope: تطبيق الفلاتر
    // =================================================================
    public function scopeFiltered($query, Request $request)
    {
        // الفلاتر المباشرة
        $filters = ['category_id', 'status_id', 'warranty_id'];
        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->get($filter));
            }
        }

        // هل لديه خصم؟
        if ($request->boolean('has_discount')) {
            $query->whereHas('discount', fn($q) => $q->where('is_active', true));
        }

        // فلتر اللون
        if ($request->filled('color_id')) {
            $query->whereHas('colors', fn($q) => $q->where('colors.id', $request->color_id));
        }

        // فلتر المقاس
        if ($request->filled('size_id')) {
            $query->whereHas('sizes', fn($q) => $q->where('sizes.id', $request->size_id));
        }

        // فلتر المادة
        if ($request->filled('material_id')) {
            $query->whereHas('materials', fn($q) => $q->where('materials.id', $request->material_id));
        }

        // فلتر السعر
        $priceFrom = $request->get('price_from');
        $priceTo = $request->get('price_to');

        if ($priceFrom && $priceTo) {
            $query->whereBetween('price', [$priceFrom, $priceTo]);
        } elseif ($priceFrom) {
            $query->where('price', '>=', $priceFrom);
        } elseif ($priceTo) {
            $query->where('price', '<=', $priceTo);
        }

        return $query;
    }

    // =================================================================
    // Scope: البحث
    // =================================================================
    public function scopeSearched($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$search}%"))
              ->orWhereHas('features', fn($f) => $f
                  ->where('name', 'like', "%{$search}%")
                  ->orWhere('value', 'like', "%{$search}%"))
              ->orWhereHas('materials', fn($m) => $m->where('name', 'like', "%{$search}%"));
        });
    }

    // =================================================================
    // Scope: الترتيب
    // =================================================================
    public function scopeSorted($query, Request $request)
    {
        $sortableFields = ['id', 'name', 'price', 'final_price', 'average_rating', 'created_at'];
        $sortBy = $request->get('sort_by', 'id');
        $direction = $request->get('sort_direction', 'desc');

        if (in_array($sortBy, $sortableFields)) {
            return $query->orderBy($sortBy, $direction);
        }

        return $query->orderBy('id', 'desc');
    }
}

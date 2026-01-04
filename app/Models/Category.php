<?php

namespace App\Models;

use App\Models\Product;
use App\Models\BannerItem;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'parent_id', 'order', 'status_id', 'image', 'sub_image'];
    protected $appends = ['full_slug'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function products()
    {
        return $this->hasMany(Product::class)->where('status_id', 1);
    }

    public function isParent()
    {
        return is_null($this->parent_id);
    }

    public function categoryBanners()
    {
        return $this->hasMany(BannerItem::class, 'category_id')
            ->whereHas('banner', function ($q) {
                $q->whereHas('type', function ($q2) {
                    $q2->where('name', 'category_slider');
                });
            })
            ->where('is_active', true);
    }

    public function getFullSlugAttribute()
    {
        if ($this->parent) {
            return $this->parent->slug . '/' . $this->slug;
        }
        return $this->slug;
    }

    // Add these accessors if needed
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getSubImageUrlAttribute()
    {
        return $this->sub_image ? asset('storage/' . $this->sub_image) : null;
    }
    public function setCoverImageAttribute($value)
    {
        $this->attributes['cover_image'] = $value;

        if (!$value) return;

        $path = Storage::disk('public')->path($value);

        if (!file_exists($path)) return;

        $optimizer = OptimizerChainFactory::create();
        $optimizer->optimize($path);
    }
}

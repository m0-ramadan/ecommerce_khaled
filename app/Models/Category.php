<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'parent_id', 'order', 'status_id', 'image', 'sub_image'];

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
        return $this->hasMany(Product::class);
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
}

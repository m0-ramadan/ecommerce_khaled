<?php

namespace App\Models;

use App\Models\Product;
use App\Models\BannerItem;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'parent_id', 'order', 'status_id', 'image', 'sub_image'];
    protected $appends = ['full_slug'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id);
            }
        });
    }

    public static function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($excludeId, function($query) use ($excludeId) {
            return $query->where('id', '!=', $excludeId);
        })->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    /**
     * كل الأبناء بكل المستويات (للفلاتر والقوائم الشجرية).
     */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    /**
     * IDs كل التصنيفات الفرعية تحت التصنيف ده (بكل المستويات).
     *
     * @return array<int, int>
     */
    public static function descendantIdsOf(int $categoryId): array
    {
        $byParent = static::query()
            ->get(['id', 'parent_id'])
            ->groupBy(fn ($category) => (int) ($category->parent_id ?? 0));

        $ids = [];
        $stack = [$categoryId];

        while ($stack) {
            $current = array_pop($stack);

            foreach ($byParent->get($current, []) as $child) {
                $ids[] = (int) $child->id;
                $stack[] = (int) $child->id;
            }
        }

        return array_values(array_unique($ids));
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
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Package extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image', 'background_image', 'design_price', 'is_active', 'order'];

    protected $casts = ['is_active' => 'boolean', 'order' => 'integer', 'design_price' => 'decimal:2'];

    public function products()
    {
        return $this->hasMany(PackageProduct::class)->orderBy('order')->orderBy('id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function makeSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'package';
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}

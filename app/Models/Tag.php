<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            $tag->slug = \Illuminate\Support\Str::slug($tag->name);
        });

        static::updating(function ($tag) {
            $tag->slug = \Illuminate\Support\Str::slug($tag->name);
        });
    }
}
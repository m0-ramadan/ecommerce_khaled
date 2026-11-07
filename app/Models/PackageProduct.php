<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageProduct extends Model
{
    protected $fillable = ['package_id', 'product_id', 'type', 'base_quantity', 'base_price', 'order'];

    protected $casts = ['base_quantity' => 'integer', 'base_price' => 'decimal:2'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tiers()
    {
        return $this->hasMany(PackageProductTier::class)->orderBy('quantity');
    }
}

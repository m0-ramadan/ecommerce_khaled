<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageProductTier extends Model
{
    protected $fillable = ['package_product_id', 'quantity', 'max_quantity', 'price'];

    protected $casts = ['quantity' => 'integer', 'max_quantity' => 'integer', 'price' => 'decimal:2'];

    public function packageProduct()
    {
        return $this->belongsTo(PackageProduct::class);
    }
}

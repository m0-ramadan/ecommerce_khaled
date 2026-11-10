<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageOrderItem extends Model
{
    protected $fillable = [
        'package_order_id', 'package_id', 'package_product_id', 'product_id',
        'package_name', 'product_name', 'type', 'quantity', 'unit_price', 'line_total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function packageOrder()
    {
        return $this->belongsTo(PackageOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

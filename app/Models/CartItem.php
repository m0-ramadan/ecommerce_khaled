<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';
    public $timestamps = true;

    public $fillable = [
        'product_id',
        'cart_id',
        'quantity',
        'total',
        'price',
        'features',
        'id',
        'smart_price',
        'smart_type'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function cart()
    {
        return $this->belongsTo('App\Models\Cart', 'cart_id');
    }
}

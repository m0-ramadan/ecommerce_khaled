<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table ='order_items';
    public $timestamps=true;

    public $fillable =['order_id','product_id','quantity','features','price','total','smart_price'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order','order_id');
    }
}

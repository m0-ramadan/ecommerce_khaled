<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table='carts';
    public $timestamps=true;

    public $fillable=['client_id','ip_address','id'];

    public function client()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\CartItem','cart_id');
    }
}

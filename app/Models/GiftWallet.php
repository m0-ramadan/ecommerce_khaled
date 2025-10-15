<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftWallet extends Model
{
    protected $table = 'giftwallet';
    public $timestamps = true;

    public $fillable = [
        'sender_name',
        'phone',
        'img_id',
        'price_id',
        'card_id',
        'message',
        'client_id',
        'price',
        'payment_type',
        'result_payment',
        'payment_number',
        'remaining',
        'image',
    ];

    public function price()
    {
        return $this->belongsTo('App\Models\Listcardprice', 'price_id');
    }
    public function images()
    {
        return $this->belongsTo('App\Models\Listcardimages', 'img_id');
    }

    public function card()
    {
        return $this->belongsTo('App\Models\ListCards', 'cart_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Client', 'user_id');
    }

    public function scopeHasRemainPoints($query)
    {
        return $query->where('remaining', '>', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}

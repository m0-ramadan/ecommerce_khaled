<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyCouponList extends Model
{
    protected $table= 'my_coupons';
    public $timestamps = true;

    public $fillable = ['client_id','coupon_id','coupon_txt'];

    public function clients()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }

    public function promocodes()
    {
        return $this->belongsTo('App\Models\PromoCode','coupon_id');
    }
}

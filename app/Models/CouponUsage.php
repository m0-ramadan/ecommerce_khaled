<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;
        protected $table = 'coupon_usage';

    protected $fillable = ['coupon_id', 'ip_address','user_id'];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}

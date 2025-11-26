<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    protected $fillable = ['coupon_id', 'user_id', 'order_id', 'session_id', 'ip_address'];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class Order extends Model
{
    protected $table = 'orders';
    public $timestamps = true;

    /* paytabs response statuses from the docs
    0  H	Hold (Authorized but on hold for further anti-fraud review)
    0  P	Pending (for refunds)
    1  A	Authorized
    2  D	Declined
    2  C	CANCELLED
    3  E	Error
    3  X    Expired
    3  V	Voided
    */

    const PAYTABS_RESPONSE_STATUSES = [
        'H' => '0',
        'P' => '0',
        'A' => '1',
        'D' => '2',
        'E' => '3',
        'X' => '3',
        'V' => '3',
        'C' => '2',
    ];

    const PAYMENT_STATUSES = [
        'UNPAID' => '0',
        'PAID' => '1',
        'CANCELLED' => '2',
        'ERROR' => '3',
    ];



const ORDER_STATUSES = [
    'منتظر التأكيد' => '0',
    'قبول الطلب' => '1',
    'رفض الطلب' => '2',
    'جارى التوصيل' => '3',
    'تم التوصيل' => '4',
    'معلق' => '5',
    'جارى التجهيز' => '6',
    'تم الغاء الطلب' => '7', // Fixed key
];
    const PAYMENT_TYPES = [
        'ONLINE' => '0',
    ];

    public $fillable = [
        'code_order',
        'client_id',
        'payment_status',
        'payment_type',
        'promo_code_id',
        'sub_total',
        'delivery_cost',
        'total',
        'status',
        'address',
        'user_name',
        'user_phone',
        'user_email',
        'country_ref_code',
        'state_id',
        'country_id',
        'payment_ref_code',
        'state_name',
        'rate_status',
        'zip_code',
        'neighborhood',
        'rate_comment',
    ];

    // protected $casts = [
    //     'created_at' => 'datetime:Y-m-d H:i:s',
    // ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d-m-y h:i A'),
        );
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function PromoCode()
    {
        return $this->belongsTo(Coupons::class, 'promo_code_id');
    }

    // public function store()
    // {
    //     return $this->belongsTo('App\Models\Store', 'store_id');
    // }

    // public function Address()
    // {
    //     return $this->belongsTo('App\Models\Address', 'address_id');
    // }


public function getOrderStatus(): string
{
    return array_search($this->status, self::ORDER_STATUSES) ?: 'منتظر التأكيد';
}

    public static function filterOrders($orders)
    {
        $filtered_orders = [];
        $filtered_orders['waiting'] = $orders->filter(function ($order) {
            return $order->status == Order::ORDER_STATUSES['PENDING'];
        });

        $filtered_orders['current'] = $orders->filter(function ($order) {
            return in_array(
                $order->status,
                [Order::ORDER_STATUSES['ACCEPTED'], Order::ORDER_STATUSES['DELIVERING'], Order::ORDER_STATUSES['PREPAREING']]
            );
        });

        $filtered_orders['holding'] = $orders->filter(function ($order) {
            return $order->status == Order::ORDER_STATUSES['HOLDING'];
        });

        $filtered_orders['old'] = $orders->filter(function ($order) {
            return in_array(
                $order->status,
                [Order::ORDER_STATUSES['DONE'], Order::ORDER_STATUSES['REJECTED']]
            );
        });

        return $filtered_orders;
    }

    public function orderitem()
    {
        return $this->hasMany('App\Models\OrderItem');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageOrder extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'order_id', 'order_number', 'status', 'status_payment',
        'payment_method', 'design_type', 'design_fee', 'design_file', 'items_total', 'shipping_amount', 'total_amount', 'shipping_address',
        'customer_name', 'customer_phone', 'customer_email', 'delivery_option_name',
        'delivery_option_id', 'oto_order_id', 'notes',
    ];

    protected $casts = [
        'items_total' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'design_fee' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(PackageOrderItem::class)->orderBy('id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

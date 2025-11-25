<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'shipping_address',
        'billing_address',
        'payment_method',
        'transaction_id',
        'shipped_at',
        'delivered_at',
        'notes',
        'shipping_amount',
        'subtotal',
        'order_number',
    ];
    public $table = 'orders';
}

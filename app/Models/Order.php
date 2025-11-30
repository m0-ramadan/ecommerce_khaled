<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'address_id',           // تمت الإضافة
        'status',
        'subtotal',
        'shipping_amount',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'payment_method',
        'transaction_id',
        'notes',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'shipped_at'   => 'datetime',
        'delivered_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'subtotal'     => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount'   => 'decimal:2',
    ];

    // ==================== العلاقات ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class); // جدول addresses
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()  // ← اسم جديد
{
    return $this->hasMany(OrderItem::class);
}

    // رقم الطلب بشكل جميل (مثال: ORD-2025-000123)
    public function getFormattedOrderNumberAttribute()
    {
        return $this->order_number;
    }

    // حالة الطلب بالعربي
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending'     => 'قيد الانتظار',
            'processing'  => 'تحت المعالجة',
            'shipped'     => 'تم الشحن',
            'delivered'   => 'تم التسليم',
            'cancelled'   => 'ملغي',
            'refunded'    => 'تم الاسترداد',
            default       => 'غير معروف',
        };
    }
}
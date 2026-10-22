<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;

        const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';
    
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'address_id',           // تمت الإضافة
        'status',
        'subtotal',
        'shipping_amount',
        'discount_amount',
        'points_used',
        'points_discount',
        'points_awarded',
        'points_awarded_at',
        'points_refunded_at',
        'tax_amount',
        'total_amount',
        'payment_method',
        'transaction_id',
        'shipping_address',
        'notes',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipped_at',
        'delivered_at',
        'status_payment',
        'shipping_method_name',
        'oto_order_id',
    ];

    protected $casts = [
        'shipped_at'   => 'datetime',
        'delivered_at' => 'datetime',
        'points_awarded_at' => 'datetime',
        'points_refunded_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'subtotal'     => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'points_discount' => 'decimal:2',
        'points_used' => 'integer',
        'points_awarded' => 'integer',
        'tax_amount'   => 'decimal:2',
    ];

    // ==================== العلاقات ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }
    public function getPaymentMethodLabelAttribute()
    {
        return $this->paymentMethod?->name;
    }


    public function address()
    {
        return $this->belongsTo(UserAddress::class); // جدول addresses
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()  // ← اسم جديد
    {
        return $this->hasMany(OrderItem::class);
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
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
            'completed'   => 'مكتمل',
            'shipped'     => 'تم الشحن',
            'delivered'   => 'تم التسليم',
            'cancelled'   => 'ملغي',
            'refunded'    => 'تم الاسترداد',
            default       => 'غير معروف',
        };
    }

    
    // في نموذج Order، أضف هذه العلاقة
public function shipments()
{
    return $this->hasMany(Shipment::class);
}

public function activeShipment()
{
    return $this->hasOne(Shipment::class)->latest();
}

public function getHasShipmentAttribute()
{
    return $this->shipments()->exists();
}

public function getShipmentStatusAttribute()
{
    return $this->activeShipment?->status;
}

public function getTrackingNumberAttribute()
{
    return $this->activeShipment?->tracking_number;
}
}

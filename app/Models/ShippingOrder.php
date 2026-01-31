<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingOrder extends Model
{
    protected $fillable = [
        'order_id',
        'oto_order_id',
        'oto_tracking_number',
        'shipping_service_id',
        'status',

        // بيانات المرسل
        'sender_name',
        'sender_phone',
        'sender_email',
        'sender_city',
        'sender_district',
        'sender_address',
        'sender_postal_code',
        'short_address_code',

        // بيانات المستلم
        'receiver_name',
        'receiver_phone',
        'receiver_email',
        'receiver_city',
        'receiver_district',
        'receiver_address',
        'receiver_postal_code',
        'short_address_code',

        // تفاصيل الشحنة
        'pieces_count',
        'weight',
        'length',
        'width',
        'height',
        'declared_value',
        'content_type',
        'content_description',

        // معلومات الدفع
        'payment_type',
        'shipping_cost',
        'cash_on_delivery_amount',
        'insurance_amount',
        'total_amount',
        'who_pays',

        // معلومات الخدمة
        'delivery_company',
        'service_type',
        'pickup_location',
        'delivery_type',

        // معلومات OTO
        'oto_response',
        'oto_labels',
        'estimated_delivery_date',
        'actual_delivery_date',

        // معلومات إضافية
        'notes',
        'accessToken'
    ];

    protected $casts = [
        'weight' => 'decimal:3',
        'declared_value' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'cash_on_delivery_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'oto_response' => 'array',
        'oto_labels' => 'array',
        'estimated_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime'
    ];

    // العلاقات
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function tracking()
    {
        return $this->hasMany(ShipmentTracking::class);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $labels = [
            'new' => 'جديد',
            'pending' => 'قيد الانتظار',
            'payment_confirmed' => 'تم تأكيد الدفع',
            'address_confirmed' => 'تم تأكيد العنوان',
            'order_confirmed' => 'تم تأكيد الطلب',
            'shipment_created' => 'تم إنشاء الشحنة',
            'going_to_pickup' => 'في طريق الاستلام',
            'arrived_pickup' => 'وصل لنقطة الاستلام',
            'picked_up' => 'تم الاستلام',
            'in_transit' => 'قيد النقل',
            'out_for_delivery' => 'في طريق التوصيل',
            'delivered' => 'تم التوصيل',
            'undelivered' => 'فشل التوصيل',
            'cancelled' => 'ملغي',
            'returned' => 'مرتجع',
            'on_hold' => 'معلق',
            'lost_damaged' => 'مفقود/تالف'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getCurrentTrackingAttribute()
    {
        return $this->tracking()->latest('event_date')->first();
    }
}

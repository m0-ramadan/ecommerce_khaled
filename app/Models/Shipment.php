<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_PICKED_UP = 'picked_up';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_RETURNED = 'returned';

    protected $table = 'shipments';

    protected $fillable = [
        'order_id',
        'external_shipment_id', // ID من OTO
        'tracking_number',
        'status',
        'carrier',
        'service_type',
        'delivery_option_id',
        'delivery_option_name',
        'estimated_delivery_date',
        'actual_delivery_date',
        'pickup_date',
        'weight',
        'height',
        'width',
        'length',
        'shipping_cost',
        'insurance_amount',
        'shipping_label_url',
        'tracking_url',
        'shipment_details', // JSON مع تفاصيل إضافية
        'notes',
        'failure_reason',
        'sender_info', // JSON
        'recipient_info', // JSON
        'customs_value',
        'customs_currency',
        'package_count',
        'is_return',
        'return_reason',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'estimated_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'pickup_date' => 'datetime',
        'shipping_cost' => 'decimal:2',
        'insurance_amount' => 'decimal:2',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'width' => 'decimal:2',
        'length' => 'decimal:2',
        'shipment_details' => 'array',
        'sender_info' => 'array',
        'recipient_info' => 'array',
        'is_return' => 'boolean',
    ];

    // ==================== العلاقات ====================

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function trackingHistory()
    {
        return $this->hasMany(ShipmentTrackingHistory::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ==================== Attributes ====================

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'قيد الانتظار',
            self::STATUS_PROCESSING => 'تحت المعالجة',
            self::STATUS_PICKED_UP => 'تم الاستلام',
            self::STATUS_IN_TRANSIT => 'في الطريق',
            self::STATUS_OUT_FOR_DELIVERY => 'جاهز للتسليم',
            self::STATUS_DELIVERED => 'تم التسليم',
            self::STATUS_FAILED => 'فشل التسليم',
            self::STATUS_CANCELLED => 'ملغي',
            self::STATUS_RETURNED => 'مرتجع',
            default => 'غير معروف',
        };
    }

    public function getPackageDimensionsAttribute()
    {
        return "{$this->length} × {$this->width} × {$this->height} سم";
    }

    public function getTotalDeclaredValueAttribute()
    {
        if ($this->customs_value && $this->insurance_amount) {
            return $this->customs_value + $this->insurance_amount;
        }
        return $this->customs_value ?? 0;
    }

    public function isDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isInTransit()
    {
        return in_array($this->status, [
            self::STATUS_PICKED_UP,
            self::STATUS_IN_TRANSIT,
            self::STATUS_OUT_FOR_DELIVERY
        ]);
    }
}
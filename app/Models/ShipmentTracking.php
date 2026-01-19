<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    protected $fillable = [
        'shipping_order_id',
        'status',
        'status_ar',
        'description',
        'description_ar',
        'location',
        'event_date',
        'oto_data'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'oto_data' => 'array'
    ];

    public function shippingOrder()
    {
        return $this->belongsTo(ShippingOrder::class);
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return $this->status_ar ?: $this->status;
    }

    public function getDescriptionTextAttribute()
    {
        return $this->description_ar ?: $this->description;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShipmentTrackingHistory extends Model
{
    use HasFactory;

    protected $table = 'shipment_tracking_history';

    protected $fillable = [
        'shipment_id',
        'status',
        'description',
        'location',
        'event_date',
        'event_time',
        'updated_by',
        'notes',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'event_time' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'shipment_id',
        'closed',
        'value_representative',
        'value_company',
        'shipping_company_price'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}

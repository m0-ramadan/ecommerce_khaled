<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationeryDeliveryArea extends Model
{
    protected $fillable = ['name', 'price', 'is_active', 'sort_order'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}

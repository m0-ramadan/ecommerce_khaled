<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'key', 'is_active','icon','is_payment','fee_percent'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
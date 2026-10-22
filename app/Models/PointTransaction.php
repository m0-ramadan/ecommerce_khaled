<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'order_id',
        'type',
        'points',
        'balance_after',
        'description',
        'meta',
    ];

    protected $casts = [
        'points' => 'integer',
        'balance_after' => 'integer',
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PhoneOtp extends Model
{
    protected $fillable = [
        'phone',
        'otp',
        'attempts',
        'expires_at',
        'used_at',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'attempts' => 'integer',
    ];

    public function isExpired(): bool
    {
        return Carbon::now()->gt($this->expires_at);
    }

    public function isUsed(): bool
    {
        return ! is_null($this->used_at);
    }
}

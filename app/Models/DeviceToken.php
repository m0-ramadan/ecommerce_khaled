<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'device_token',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}

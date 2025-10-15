<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;
    protected $fillable = [
        'code', 'time', 'mount', 'start_date', 'num_use_user', 'expiry_date', 'status', 'num_times'
    ];
}

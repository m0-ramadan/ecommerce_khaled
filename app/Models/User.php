<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

   // Define the fillable attributes
    protected $fillable = [
        'name',
        'email',
        'password',
        'txt',
        'device_key',
        'firebase_id',
        'type',
        'promo_code_id',
        'class',
    ];

    // Define attributes to be cast
    protected $casts = [
        'email_verified_at' => 'datetime',
        'type' => 'integer',
        'promo_code_id' => 'integer',
    ];

    // Define hidden attributes
    protected $hidden = [
        'password',
        'remember_token',
    ];

}
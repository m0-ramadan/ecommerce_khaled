<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'apple_id',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function notifications()
    {
        return $this->morphMany(\App\Models\Notification::class, 'notifiable');
    }
    public function favourites()
{
    return $this->hasMany(\App\Models\Favourite::class);
}

public function favouriteProducts()
{
    return $this->belongsToMany(\App\Models\Product::class, 'favourites');
}

}

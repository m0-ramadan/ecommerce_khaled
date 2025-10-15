<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable implements JWTSubject
{
    use Notifiable, HasApiTokens;
    use SoftDeletes;
    protected $table = 'clients';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'remember_token',
        'device_key',
        'state_id',
        'address',
        'firebase_id',
        'lang',
        'image',
        'percentage',
        'capital',
        'profit',
        'residual',
        'total_point',
        'reset_password_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function clientCategories()
    {
        return $this->hasMany(ClientCategory::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function reviews()
    {
        return $this->hasMany(
            'App\Models\Review',
            'client_id'
        );
    }

    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function rosters()
    {
        return $this->hasMany('App\Models\Roster');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function cart()
    {
        return $this->hasOne('App\Models\Cart');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function promoCodes()
    {
        return $this->hasMany('App\Models\PromoCode');
    }

    public function favorites()
    {
        return $this->hasMany('App\Models\Favorite');
    }

    public function firebaseTokens()
    {
        return $this->hasMany(ClientsToken::class);
    }
}

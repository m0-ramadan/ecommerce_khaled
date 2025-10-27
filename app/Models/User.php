<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'phone2',
        'id_number',
        'address',
        'wallet',
        'id_image',
        'verified',
        'email_verified_at',
        'status',
        'region_id',
        'country_id',
        'rejected_message',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function adminWallets(){
        return $this->hasMany(AdminWallet::class);
    }

    public function WalletDetails(){
        return $this->hasMany(WalletDetail::class,'user_id')->with('payment');
    }

    public function payments(){
        return $this->hasMany(Payment::class,'user_id');
    }

    public function  userBank(){
        return  $this->hasOne(UserBank::class,'user_id');
    }

    public function  userDue(){
        return  $this->hasMany(UserDue::class,'user_id');
    }

    public function subscribes(){
        return  $this->hasMany(Subscribe::class,'user_id')->where('status',1);
    }

    public function subscribe(){
        return  $this->hasOne(Subscribe::class,'user_id')->where([['remain','>', 0],['status','=',1]]);
    }

    public function shipments(){
        return $this->hasMany(Shipment::class,'user_id');

    }
    public function  userAddress(){
        return  $this->hasOne(UserAddress::class,'user_id');
    }
    
    public function scopeNotLocked($query)
    {
        return $query->where('locked', false);
    }

    public function isLocked()
    {
        return $this->locked;
    }

    public function lock()
    {
        $this->locked = true;
        $this->save();
    }

    public function unlock()
    {
        $this->locked = false;
        $this->save();
    }

}

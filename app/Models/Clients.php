<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Clients extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $fillable = [
        'name',
        'user_id',
        'region_id',
        'country_id',
        'phone',
        'phone2',
        'address',
        'status',
        'email',
        'sender_id',
        'locked',
        'birth_date',
        'type',
        'company_representative_name',
        'password',
        'remember_token',
        'otp',
        'otp_expires_at',
        'representative_phone',
        'representative_email',
        'code',
        'branch_id'
    ];

    public function deviceToken()
    {
        return $this->morphMany(DeviceToken::class, 'model');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branchs::class, 'branch_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'person_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function shipments()
    {
        return $this->morphMany(Shipment::class, 'person');
    }
    public function tasks()
    {
        return $this->morphMany(Task::class, 'person');
    }

    public function createdTransfers() // Duplicate declaration
    {
        return $this->morphMany(TransferMoney::class, 'creator', 'transfers_id_created_type', 'transfers_id_created');
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            0 => 'قيد الانتظار',
            1 => 'مفعل',
            2 => 'محظور',
            default => 'غير معروف',
        };
    }


    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }

    public function getProductSumAttribute()
    {
        return $this->products()->sum('in_stock_quantity');
    }


    // public function wallet()
    // {
    //     return $this->morphOne(WalletDetail::class, 'walletable');
    // }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'model_id',
        'amount',
        'status',
        'currency_id',
        'model_type',
        'indebtedness',
        'coin'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'wallet_detail_id');
    }
    public function walletable()
    {
        return $this->morphTo();
    }

    public function model()
    {
        return $this->morphTo();
    }

    public function walletHistories()
    {
        return $this->hasMany(WalletHistory::class, 'wallet_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    protected $table = 'wallet_history';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'transaction_date',
        'status','transfer_from_id','commission'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function walletDetail()
    {
        return $this->belongsTo(WalletDetail::class, 'wallet_id');
    }
    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_from_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SettlementRequestOffice extends Model
{
    use HasFactory;

    protected $table = 'settlement_requests_offices';

    protected $fillable = [
        'transfer_id',
        'wallet_id',
        'admin_id',
        'amount',
        'currency_id',
        'notes',
        'settled_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'settled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function transfer()
    {
        return $this->belongsTo(TransferMoney::class, 'transfer_id');
    }

    public function wallet()
    {
        return $this->belongsTo(WalletDetail::class, 'wallet_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
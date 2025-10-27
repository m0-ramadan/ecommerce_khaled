<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CurrencyAddition extends Model
{
    use HasFactory;

    protected $table = 'currency_additions';

    protected $fillable = [
        'transfer_id',
        'currency_id',
        'value','admin_id','status'
    ];

    protected $casts = [
        'value' => 'decimal:8',
    ];

    /**
     * Get the transfer that owns this currency addition
     */
    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }

    /**
     * Get the currency associated with this addition
     */
    // public function currency()
    // {
    //     return $this->belongsTo(Currency::class, 'currency_id');
    // }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyConversion extends Model
{
    use SoftDeletes;

    protected $table = 'currency_conversions';

    protected $fillable = [
        'from_currency',
        'to_currency',
        'conversion_rate',
        'conversion_transfer_rate'
    ];

    protected $casts = [
        'conversion_rate' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];



    public const CURRENCIES = [
        1 => 'دينار ليبي',
        2 => 'جنيه مصري',
        3 => 'دولار أمريكي',
        4 => 'ليرة',

    ];

    // Accessor لعرض اسم العملة بناءً على الرقم
    public function getFromCurrencyNameAttribute()
    {
        return self::CURRENCIES[$this->from_currency] ?? 'غير معروف';
    }

    public function getToCurrencyNameAttribute()
    {
        return self::CURRENCIES[$this->to_currency] ?? 'غير معروف';
    }
}

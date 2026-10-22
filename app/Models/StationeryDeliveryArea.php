<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationeryDeliveryArea extends Model
{
    public const RIYADH_NAME = 'الرياض';
    public const RIYADH_PRICE = 20.00;

    protected $fillable = ['name', 'price', 'is_active', 'sort_order'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * التوصيل داخل الرياض فقط بسعر ثابت 20 ريال.
     */
    public static function riyadh(): self
    {
        return static::query()->firstOrCreate(
            ['name' => self::RIYADH_NAME],
            ['price' => self::RIYADH_PRICE, 'is_active' => true, 'sort_order' => 1]
        );
    }
}

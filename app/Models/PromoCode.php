<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PromoCode extends Model
{
    protected $fillable = [
        'code',
        'description',
        'discount_percentage',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_percentage' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public $timestamps = false;

    public function bannerItems()
    {
        return $this->belongsToMany(BannerItem::class, 'banner_promo_codes')
                    ->withTimestamps();
    }

    /**
     * Scope: active and currently valid promo codes
     */
    public function scopeActiveAndValid($query)
    {
        $now = Carbon::now();

        return $query->where('is_active', true)
                     ->where('start_date', '<=', $now)
                     ->where('end_date', '>=', $now);
    }
}

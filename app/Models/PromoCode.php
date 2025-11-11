<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}

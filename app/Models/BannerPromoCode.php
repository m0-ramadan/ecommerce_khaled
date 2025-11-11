<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BannerPromoCode extends Pivot
{
    protected $table = 'banner_promo_codes';

    protected $fillable = [
        'banner_item_id',
        'promo_code_id',
    ];

    public $timestamps = false;
}

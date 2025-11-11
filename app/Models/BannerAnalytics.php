<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerAnalytics extends Model
{
    protected $fillable = [
        'banner_item_id',
        'views_count',
        'clicks_count',
        'date',
    ];

    public $timestamps = false;

    protected $casts = [
        'date' => 'date',
    ];

    public function bannerItem()
    {
        return $this->belongsTo(BannerItem::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerItem extends Model
{
    protected $fillable = [
        'banner_id',
        'item_order',
        'image_url',
        'image_alt',
        'mobile_image_url',
        'link_url',
        'link_target',
        // 'is_link_active',
        'product_id',
        'category_id',
        'tag_text',
        'tag_color',
        'tag_bg_color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
       
    ];

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }

    public function promoCodes()
    {
        return $this->belongsToMany(PromoCode::class, 'banner_promo_codes')
                    ->withTimestamps();
    }

    public function analytics()
    {
        return $this->hasMany(BannerAnalytics::class);
    }
    
}

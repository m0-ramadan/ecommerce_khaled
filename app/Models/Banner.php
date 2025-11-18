<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'banner_type_id',
        'section_order',
        'is_active',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(BannerType::class, 'banner_type_id');
    }

    public function items()
    {
        return $this->hasMany(BannerItem::class);
    }

    public function sliderSetting()
    {
        return $this->hasOne(SliderSetting::class);
    }

    public function gridLayout()
    {
        return $this->hasOne(BannerGridLayout::class);
    }
}

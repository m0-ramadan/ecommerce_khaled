<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SliderSetting extends Model
{
    protected $fillable = [
        'banner_id',
        'autoplay',
        'autoplay_delay',
        'loop',
        'show_navigation',
        'show_pagination',
        'slides_per_view',
        'space_between',
        'breakpoints',
    ];

    protected $casts = [
        'autoplay' => 'boolean',
        'loop' => 'boolean',
        'show_navigation' => 'boolean',
        'show_pagination' => 'boolean',
        'breakpoints' => 'array',
    ];

    public $timestamps = false;

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }
}

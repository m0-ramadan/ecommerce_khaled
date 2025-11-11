<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerGridLayout extends Model
{
    protected $fillable = [
        'banner_id',
        'grid_type',
        'desktop_columns',
        'tablet_columns',
        'mobile_columns',
        'row_gap',
        'column_gap',
    ];

    public $timestamps = false;

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }
}

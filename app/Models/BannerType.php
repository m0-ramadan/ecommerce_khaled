<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerType extends Model
{
    protected $fillable = ['name', 'description'];

    public $timestamps = false;

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }
}

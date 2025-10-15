<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Banner extends Model
{
    use HasTranslations;
    public $translatable = ['image','image_mop'];

    protected $table = 'banners';
    public $timestamps = true;

    public $fillable =['image','text','image_mop'];
}

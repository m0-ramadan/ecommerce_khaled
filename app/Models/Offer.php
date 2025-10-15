<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Offer extends Model
{
    use HasTranslations;
    public $translatable = ['title','title2','title3','title4','title5','content','details','image','mob_image'];

    protected $table = 'offers';
    public $timestamps = true;

    protected $fillable = ['title','title2','title3','title4','title5','content','image','discount','details','mob_image'];
}

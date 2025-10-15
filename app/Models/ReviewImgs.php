<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ReviewImgs extends Model
{
    // use HasTranslations;
    // public $translatable = ['title','content'];

    protected $table='reviewimgs';
    public $timestamps=true;

    public $fillable=['list_id','img'];
}

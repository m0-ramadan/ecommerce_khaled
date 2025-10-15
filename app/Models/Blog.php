<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Blog extends Model
{
    use HasTranslations;

    public $translatable = ['title','content'];

    protected $table = 'blogs';
    public $timestamps = true;

    public $fillable = ['title','content','image','time'];
}

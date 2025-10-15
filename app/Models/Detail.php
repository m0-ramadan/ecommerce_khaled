<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Detail extends Model
{
    use HasTranslations;
    public $translatable = ['title','description',];
    protected $table='details';
    public $timestamps = true;

    protected $fillable=['title','description','image'];
}
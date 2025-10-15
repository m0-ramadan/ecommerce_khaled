<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasTranslations;
    public $translatable = ['title','content','details'];

    protected $table='services';
    public $timestamps=true;

    public $fillable=['title','content','img','details'];
}
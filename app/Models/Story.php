<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Story extends Model
{
    use HasTranslations;
    public $translatable = ['title','content'];

    protected $table='stories';
    public $timestamps=true;

    public $fillable=['title','content','image'];
}

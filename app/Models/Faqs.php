<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class Faqs extends Model
{
     use HasTranslations;
    protected $table= 'faqs';
    public $timestamps = true;
    public $translatable = ['questions','answer'];
    public $fillable = ['questions','answer'];
}

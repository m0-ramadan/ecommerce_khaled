<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Translatable\HasTranslations;

class Store extends Authenticatable
{
    use HasTranslations;
    public $translatable = ['name','address','about','judgments','replacement'];

    protected $table = 'stores';
    public $timestamps = true;

    public $fillable = ['name','image','type','is_active','phone','location','address','about','facebook','instagram','twitter',
        'judgments','replacement','details','password'];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SubCategory extends Model
{
    use HasTranslations;
    public $translatable = ['name','details'];

    protected $table='sub_categories';
    public $timestamps =true;

    public $fillable =['name','details','image','category_id','is_active','store_id','cat_title','cat_meta','alt_txt','img_title','slug'];

    public function category()
    {
        return $this->belongsTo('App\Models\Category','category_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}

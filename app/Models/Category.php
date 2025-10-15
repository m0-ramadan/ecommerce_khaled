<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;


class Category extends Model
{
    use Notifiable;

    use HasTranslations;
    public $translatable = ['name','description', 'banner', 'image','image_mop'];
    protected $table='categories';
    public $timestamps=true;

    public $fillable = ['name','image','image_mop','description','store_id','is_active','type'];

    public function clientCategory()
    {
        return $this->hasMany(ClientCategory::class);
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store','store_id');
    }

    public function subCategories()
    {
        return $this->hasMany('App\Models\SubCategory');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProductItem extends Model
{
    use HasTranslations;
    public $translatable = ['name'];

    protected $table='products';
    public $timestamps=true;

    public $fillable=['name','price','quantity','image'];


    public function scopeActive($query)
    {
        return $query->where('is_active',true);
    }

    public function subcategory()
    {
        return $this->belongsTo('App\Models\Subcategory','sub_category_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category','category_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review','product_id');
    }
    
        public function productfeatures()
    {
        return $this->hasMany('App\Models\ProductFeatures','product_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }

    public function Favorites()
    {
        return $this->hasMany('App\Models\Favorite','product_id');
    }

    public function Rosters()
    {
        return $this->hasMany('App\Models\RosterItem','product_id');
    }
}

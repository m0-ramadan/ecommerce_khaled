<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class ProductFeatures extends Model
{
    
     use HasTranslations;
    public $translatable = ['name','description'];
    
    protected $table='product_features';
    public $timestamps=true;

    public $fillable=['id','name','description','product_id'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProductFeature extends Model
{
    use HasTranslations;
    public $translatable = ['name','description'];

    protected $table='product_features';
    public $timestamps=true;

    public $fillable=['name','product_id','description'];

 
 
    public function Product(){
     
     return $this->belongsto(Product::class,'product_id','id');
     
      }

}
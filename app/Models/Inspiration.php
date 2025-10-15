<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Inspiration extends Model
{
     use SoftDeletes;
    protected $table = 'inspirations';
    public $timestamps = true;

    public $fillable = ['image','url_link','link_id','type','link_id1','link_id2'];
    
    
    public function product(){
       return  $this->belongsto(Product::class,'link_id','id');
    }
}

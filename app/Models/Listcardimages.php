<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Listcardimages extends Model
{
    
     use HasTranslations;
     protected $table = 'listcard_images';
         public $translatable = ['name'];
    public $timestamps = true;
     protected $fillable = array('list_id','image','name');



    public function list_id()
    {
        return $this->belongsTo('App\Models\ListCards');
    }


}

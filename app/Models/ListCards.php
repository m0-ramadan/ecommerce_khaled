<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ListCards extends Model
{
    
     use HasTranslations;
     protected $table = 'list_cards';
         public $translatable = ['name'];
    public $timestamps = true;
     protected $fillable = array('code','price','counts','end','start_date','name');



    public function countries()
    {
        return $this->belongsTo('App\Models\Country');
    }


}

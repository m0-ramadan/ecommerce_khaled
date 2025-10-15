<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    
     use HasTranslations;
    public $translatable = ['name'];
    protected $table = 'cities';
    public $timestamps = true;

    protected $fillable = array('name','countries_id');



    public function countries()
    {
        return $this->belongsTo('App\Models\Country');
    }


}

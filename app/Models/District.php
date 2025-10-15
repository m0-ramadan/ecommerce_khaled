<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
class District extends Model
{
    
    use HasTranslations;
    public $translatable = ['name'];
    protected $table = 'districts';
    public $timestamps = true;

    protected $fillable = array('name','cities_id');

    public function clients()
    {
        return $this->hasMany('App\Models\Client');
    }


    public function countries()
    {
        return $this->belongsTo('App\Models\Country');
    }


}

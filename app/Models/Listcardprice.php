<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Listcardprice extends Model
{

    // use HasTranslations;
    protected $table = 'listcard_price';
    public $timestamps = true;
    protected $fillable = array('list_id', 'price', 'counts');



    public function list_id()
    {
        return $this->belongsTo('App\Models\ListCards');
    }
}

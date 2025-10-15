<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    public $fillable=['name','src','product_id','alttxt','titletxt'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }
}

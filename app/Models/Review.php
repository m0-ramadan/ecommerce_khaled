<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table='reviews';
    public $timestamps=true;

    public $fillable=['comment','image','client_id','rate','product_id'];

    public function client()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }
}

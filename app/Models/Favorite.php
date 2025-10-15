<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table= 'favorites';
    public $timestamps = true;

    public $fillable = ['client_id','product_id','list_id'];

    public function fav()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }

    public function products()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }

      public function lists()
    {
        return $this->belongsTo('App\Models\ListFavorites','list_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListFavorites extends Model
{
    protected $table='list_favorites';
    public $timestamps=true;
    public $fillable = ['client_id','name','img'];

    public function lists()
    {
        return $this->belongsTo('App\Models\Favorite','list_id');
    }
    
    public function favorites()
    {
        return $this->hasMany('App\Models\Favorite','list_id');
    }
    
}

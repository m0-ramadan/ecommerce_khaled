<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table='addresses';
    public $timestamps=true;

    public $fillable=['location','client_id'];

    public function client()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }
}

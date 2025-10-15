<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListImages extends Model
{
    protected $table='reviewimgs';
    public $timestamps = true;
    public $fillable = ['list_id','img'];
}

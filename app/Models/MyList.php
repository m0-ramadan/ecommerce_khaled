<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyList extends Model
{
    protected $table='my_lists';
    public $timestamps=true;
    public $fillable = ['client_id','product_id'];
}

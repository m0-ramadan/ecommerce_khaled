<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subscribe extends Model
{
    protected $table ='subscribes';
    public $timestamps = true;

    public $fillable = ['email'];
}

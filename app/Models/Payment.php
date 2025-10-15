<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table='payments';
    public $timestamps=true;

    public $fillable=['image','link','type'];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table='employees';
    public $timestamps=true;

    public $fillable=['name','email','address','message','file'];
}

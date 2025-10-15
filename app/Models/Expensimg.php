<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 class Expensimg extends Model
{
 
    protected $table= 'expensimg';
    public $timestamps = true;
    public $fillable = ['details','total_money'];
}

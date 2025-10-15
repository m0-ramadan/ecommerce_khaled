<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $table = 'supports';
    public $timestamps = true;

    public $fillable = ['name','phone','message','notes','type','email','file'];
}

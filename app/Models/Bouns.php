<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bouns extends Model
{
    protected $table = 'bounses';
    public $timestamps = true;

    public $fillable = ['start_bouns','point','end_bonus'];
}

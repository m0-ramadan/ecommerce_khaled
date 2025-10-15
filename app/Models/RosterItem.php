<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RosterItem extends Model
{
    protected $table = 'roster_items';
    public $timestamps =true;

    public $fillable = ['roster_id','product_id'];
}

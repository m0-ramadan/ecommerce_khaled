<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    protected $table='rosters';
    public $timestamps = true;

    public $fillable = ['client_id','link','name'];

    public function items()
    {
        return $this->hasMany('App\Models\RosterItem','roster_id');
    }
}

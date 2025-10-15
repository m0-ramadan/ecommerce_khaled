<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table='notifications';
    public $timestamps=true;

    public $fillable = ['title','body','client_id'];

    public function notifications()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }
}

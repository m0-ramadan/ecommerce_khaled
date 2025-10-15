<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BounsPoint extends Model
{
    protected $table = 'bouns_points';
    public $timestamps = true;

    public $fillable = ['bouns_id','order_id','client_id','order_point'];

    public function orders()
    {
        return $this->belongsTo('App\Models\Order','order_id');
    }
}

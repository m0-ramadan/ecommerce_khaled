<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyAbout extends Model
{
    protected $table='policy_about';
    public $timestamps=true;

    public $fillable=['txt_name','id','type'];
    
    public function Policy()
    {
        return $query->where('type',1);
    }
    
}

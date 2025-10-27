<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable=[
        'region_id','country_id','user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function userAdress(){
        return $this->belongsTo(Region::class,'region_id');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
}

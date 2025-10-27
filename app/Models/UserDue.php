<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDue extends Model
{
    use HasFactory;
    protected $fillable=[
       'user_id', 'amount','reference_number',
'image','status','transfer_date'
    ];


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}

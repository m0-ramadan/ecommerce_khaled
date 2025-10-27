<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBank extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id','full_name','bank_name','bank_account_number'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}

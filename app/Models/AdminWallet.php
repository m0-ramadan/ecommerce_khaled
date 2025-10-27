<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminWallet extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'amount',
        'type','price_operation','status_operation','created_at'
    ];
    
     public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}

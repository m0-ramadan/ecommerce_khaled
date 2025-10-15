<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashback extends Model
{
    use HasFactory;
    
    protected $fillable = ['client_id', 'points', 'point_usage'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

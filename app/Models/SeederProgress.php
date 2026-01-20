<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeederProgress extends Model
{
    protected $table = 'seeder_progress';
    
    protected $fillable = [
        'seeder_name',
        'last_processed_id',
        'last_processed_page',
        'total_processed',
        'status',
        'completed_at'
    ];
    
    protected $casts = [
        'completed_at' => 'datetime'
    ];
}
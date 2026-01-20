<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessingLog extends Model
{
    protected $table = 'processing_logs';
    
    protected $fillable = [
        'seeder_progress_id',
        'product_id',
        'external_product_id',
        'product_name',
        'step',
        'status',
        'details',
        'options_count',
        'processing_time',
        'memory_usage'
    ];
    
    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // علاقة مع SeederProgress
    public function seederProgress()
    {
        return $this->belongsTo(SeederProgress::class);
    }
    
    // علاقة مع المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
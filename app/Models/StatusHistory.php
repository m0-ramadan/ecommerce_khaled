<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    protected $table = 'status_history';
    
    protected $fillable = ['status_id', 'statusable_id', 'statusable_type','explain','image','admin_update'];

    /**
     * Get the owning statusable model (polymorphic relationship).
     */
    public function statusable()
    {
        return $this->morphTo();
    }
        public function note()
    {
        return $this->belongsTo(Note::class, 'explain');
    }
}
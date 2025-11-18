<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'url', 'alt', 'type', 'order', 'is_active'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}

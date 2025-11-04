<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    protected $fillable = ['product_id', 'duration_months'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
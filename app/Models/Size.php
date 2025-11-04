<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['product_id', 'value'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
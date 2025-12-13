<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'image', 'hex_code', 'additional_price'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}

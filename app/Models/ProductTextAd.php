<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTextAd extends Model
{
    protected $fillable = [
        'product_id',
        'name',
    ];
public $timestamps = false;
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'material_product')
                    ->withPivot(['quantity', 'unit'])
                    ->withTimestamps();
    }
}

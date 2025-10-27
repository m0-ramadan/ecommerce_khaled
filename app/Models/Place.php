<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'place_type_id', 'phone', 'address', 'images'];

    public function placeType()
    {
        return $this->belongsTo(PlaceType::class);
    }
        public function meals()
    {
        return $this->hasMany(Meal::class);
    }
}
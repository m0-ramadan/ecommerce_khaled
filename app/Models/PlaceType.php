<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceType extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['type_name'];

    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
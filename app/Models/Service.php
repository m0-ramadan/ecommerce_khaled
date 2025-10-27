<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // Table name (optional, if not using default naming convention)
    protected $table = 'services';

    // Mass assignable attributes
    protected $fillable = ['name', 'image','parent_id','description'];

    // // Cast the `name` column to JSON
    // protected $casts = [
    //     'name' => 'array',
    // ];
        public function parent()
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    // Define the children relationship
    public function children()
    {
        return $this->hasMany(Service::class, 'parent_id');
    }
}

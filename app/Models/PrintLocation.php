<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type','additional_price'
    ];
    public $table = 'print_locations';
}

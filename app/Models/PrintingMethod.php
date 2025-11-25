<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintingMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'base_price',
    ];
    public $table = 'printing_methods';
}

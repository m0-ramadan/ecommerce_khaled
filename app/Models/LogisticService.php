<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticService extends Model
{
    use HasFactory;

    protected $fillable=[
    'title','description','details','image','detail_image'
    ];
}

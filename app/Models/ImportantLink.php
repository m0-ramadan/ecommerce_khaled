<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportantLink extends Model
{
    protected $fillable = ['key', 'name', 'description', 'url'];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class NewSetting extends Model
{
    use HasFactory, HasTranslations;

    const UPLOAD_PATH = 'uploads/settings/';
    protected $fillable = ['name', 'value', 'title'];
    protected $translatable = ['title'];
}

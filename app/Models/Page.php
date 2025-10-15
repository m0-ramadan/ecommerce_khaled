<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;
    public $translatable = ['title', 'content', 'content_app'];
    protected $table = 'pages';
    public $timestamps = true;

    public $fillable = [
        'title',
        'content',
        'content_app',
        'image',
        'file',
        'type',
        'footer',
        'bg_image',
        'app_view'
    ];
}

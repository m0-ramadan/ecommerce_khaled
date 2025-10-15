<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;


class Title extends Model
{
    use Notifiable;

    use HasTranslations;
    public $translatable = ['title'];
    protected $table='titles';
    public $timestamps=true;

    public $fillable = ['title','image','page_name'];

}

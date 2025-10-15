<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;


class Construction extends Model
{
    use Notifiable;

    use HasTranslations;
    public $translatable = ['title'];
    protected $table='constructions';
    public $timestamps=true;

    public $fillable = ['title','image','link'];


}

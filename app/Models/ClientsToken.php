<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Translatable\HasTranslations;
use Illuminate\Foundation\Auth\User as Authenticatable;


class ClientsToken extends Model
{
    protected $table = 'clients_token';
    public $timestamps = true;
    public $fillable = ['firebase_id','client_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Expenses extends Model
{
    protected $table = 'expenses';
    const PATH = 'expenses';

    public $timestamps = true;
    public $fillable = ['details', 'total_money', 'client_id', 'file'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

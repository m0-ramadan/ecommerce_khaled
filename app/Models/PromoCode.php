<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $table= 'promo_codes';
    public $timestamps = true;

    public $fillable=[
        'code',
        'value',
        'status',
        'start_date',
        'end',
        'counts',
        'main',
        'client_id',
    ];
    
    public function client()
    {
        return $this->belongTo(Client::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'promo_code_id');
    }
}

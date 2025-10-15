<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    protected $table = 'gift_cards';
    public $timestamps = true;

    public $fillable =['receiver','phone','address','link','message','client_id','type','order_id','qr_image'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $fillable=[
        'shipment_company_id',
        'code',
        'discount',
        'type' ,
        'from',
        'to' ,
        'time',
    ];


    public function company(){
        return $this->belongsTo(ShipmentCompany::class,'shipment_company_id');
    }
}

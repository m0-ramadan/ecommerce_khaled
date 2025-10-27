<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertiesShipment extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function shipmentsClients()
    {
        
        return $this->hasMany(ShipmentsClient::class, 'properties_shipment_id');
    }
    public function shipmentsCompanies()
{
    return $this->belongsToMany(ShipmentsCompany::class, 'shipments_company_properties_shipment');
}
}
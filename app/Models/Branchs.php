<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchs extends Model
{
    use HasFactory;

    protected $table = 'branchss'; // Use custom table name

    protected $fillable = [
        'name',
        'phone1',
        'phone2',
        'address',
        'link_address',
        'region_id',
        'country_id',
        'price',
        'key',
        'code'
    ];
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
    public function vaults()
    {
        return $this->hasMany(Vault::class, 'branch_id');
    }
    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'branches_from');
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'branch_id');
    }
}

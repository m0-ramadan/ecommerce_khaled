<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'country_ar',
        'status',
        'item_order',
        'message',
        'collection_commission',
        'currency_id'
    ];

    /**
     * Get the regions associated with the country.
     */
    public function region()
    {
        return $this->hasMany(Region::class);
    }
    public function regions()
    {
        return $this->hasMany(Region::class);
    }
    public function branchs()
    {
        return $this->hasMany(Branchs::class);
    }

}
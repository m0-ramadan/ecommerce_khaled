<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchssPricing extends Model
{
    protected $table = 'branchss_pricing';

    protected $fillable = [
        'branchss_id_1',
        'city_id',
        'price','currency','city_id_from'
    ];

    // Relationship with the first branch
    public function branchOne()
    {
        return $this->belongsTo(Branchs::class, 'branchss_id_1');
    }

    // Relationship with the second branch
    public function city()
    {
        return $this->belongsTo(Region::class, 'city_id');
    }
    
        public function city_from()
    {
        return $this->belongsTo(Region::class, 'city_id_from');
    }
    
    
}
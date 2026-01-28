<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryOption extends Model
{
    use HasFactory;

    protected $table = 'delivery_options';

    protected $fillable = [
        'external_id',
        'name',
        'description',
        'carrier',
        'service_type',
        'estimated_min_days',
        'estimated_max_days',
        'base_cost',
        'currency',
        'is_active',
        'city',
        'requirements',
        'limitations'
    ];

    protected $casts = [
        'base_cost' => 'decimal:2',
        'is_active' => 'boolean',
        'requirements' => 'array',
        'limitations' => 'array'
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'delivery_option_id', 'external_id');
    }
}
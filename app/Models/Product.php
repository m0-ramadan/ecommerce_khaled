<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'brand',
        'in_stock_quantity',
        'reorder_limit',
        'minimum_stock',
        'location_in_stock',
        'product_details',
        'purchase_price',
        'sale_price',
        'discounts',
        'expected_profit_margin',
        'supplier_name',
        'supplier_contact_information',
        'expected_delivery_time',
        'status',
        'date_added_to_stock',
        'date_last_updated_to_stock',
        'expiry_date',
        'unit_type',
        'product_size',
        'person_id',
        'person_type',
        'image',
        'currency',
        'branch_id'
    ];

    /**
     * Get the associated person (polymorphic relationship).
     */
    public function person()
    {
        return $this->morphTo();
    }
    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function shipmentsClient()
    {
        return $this->belongsTo(Shipment::class, 'shipments_client_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id', 'product_id', 'size_id', 'color_id', 'printing_method_id',
        'print_locations', 'embroider_locations', 'selected_options',
        'design_service_id', 'quantity', 'price_per_unit', 'line_total', 'is_sample','note','quantity_id','image_design'
    ];

    protected $casts = [
        'print_locations' => 'array',
        'embroider_locations' => 'array',
        'selected_options' => 'array',
    ];

    public function cart() { return $this->belongsTo(Cart::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function size() { return $this->belongsTo(Size::class); }
    public function color() { return $this->belongsTo(Color::class); }
    public function printingMethod() { return $this->belongsTo(PrintingMethod::class); }
    public function designService() { return $this->belongsTo(DesignService::class); }
}
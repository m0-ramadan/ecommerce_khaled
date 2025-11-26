<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'size_id',
        'color_id',
        'printing_method_id',
        'print_locations',         // JSON
        'embroider_locations',     // JSON
        'selected_options',        // JSON
        'design_service_id',
        'quantity',
        'price_per_unit',
        'total_price',
        'is_sample',
        'note',
        'quantity_id',             // إذا كنت تستخدمه من pricing_tiers
        'image_design',            // رابط الصورة المرفوعة
    ];

    protected $casts = [
        'print_locations'     => 'array',
        'embroider_locations' => 'array',
        'selected_options'    => 'array',
        'price_per_unit'      => 'decimal:4',
        'total_price'         => 'decimal:4',
        'is_sample'           => 'boolean',
    ];

    // ==================== العلاقات ====================

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function printingMethod()
    {
        return $this->belongsTo(PrintingMethod::class);
    }

    public function designService()
    {
        return $this->belongsTo(DesignService::class);
    }

    // ملحقات مفيدة
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_price, 2) . ' ج.م';
    }

    public function getPrintLocationsNamesAttribute()
    {
        if (!$this->print_locations) return [];
        return PrintLocation::whereIn('id', $this->print_locations)->pluck('name')->toArray();
    }

    public function getEmbroiderLocationsNamesAttribute()
    {
        if (!$this->embroider_locations) return [];
        return PrintLocation::whereIn('id', $this->embroider_locations)->pluck('name')->toArray();
    }
}
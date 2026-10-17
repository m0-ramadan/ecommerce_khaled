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
        return number_format($this->total_price, 2) . ' ر.س';
    }

    public function getPrintLocationsNamesAttribute()
    {
        $locationIds = $this->normalizeLocationIds($this->print_locations);

        if ($locationIds === []) return [];

        return PrintLocation::whereIn('id', $locationIds)->pluck('name')->toArray();
    }

    public function getEmbroiderLocationsNamesAttribute()
    {
        $locationIds = $this->normalizeLocationIds($this->embroider_locations);

        if ($locationIds === []) return [];

        return PrintLocation::whereIn('id', $locationIds)->pluck('name')->toArray();
    }

    private function normalizeLocationIds(mixed $locations): array
    {
        // بعض السجلات القديمة تحتوي JSON مشفّرًا مرتين مثل "[]".
        for ($attempt = 0; $attempt < 2 && is_string($locations); $attempt++) {
            $decoded = json_decode($locations, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                break;
            }
            $locations = $decoded;
        }

        if ($locations === null || $locations === '' || $locations === false) {
            return [];
        }

        if (!is_array($locations)) {
            $locations = [$locations];
        }

        return collect($locations)
            ->flatten()
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }
}

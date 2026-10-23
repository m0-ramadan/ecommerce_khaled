<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class StationeryPrice extends Model
{
    use HasFactory;

    protected $table = 'stationery_prices';

    protected $fillable = [
        'service_type',
        'paper_type',
        'paper_size',
        'side_type',
        'orientation',
        'bw_price',
        'color_price',
        'file_bw_price',
        'file_color_price',
        'double_sided_price',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'bw_price' => 'decimal:2',
        'color_price' => 'decimal:2',
        'file_bw_price' => 'decimal:2',
        'file_color_price' => 'decimal:2',
        'double_sided_price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static function defaultSizes(): array
    {
        return ['A4', 'A3', 'A5', 'A2', 'A1'];
    }

    public static function sizeDimensions(): array
    {
        return [
            'A1' => '59.4 × 84.1 cm',
            'A2' => '42.0 × 59.4 cm',
            'A3' => '29.7 × 42.0 cm',
            'A4' => '21.0 × 29.7 cm',
            'A5' => '14.8 × 21.0 cm',
        ];
    }

    public static function paperTypes(bool $onlyActive = true): array
    {
        if (Schema::hasTable('stationery_paper_types')) {
            $query = StationeryPaperType::query();
            if ($onlyActive) {
                $query->where('is_active', true);
            }

            return $query
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->groupBy('service_type')
                ->map(fn ($types) => $types->pluck('name', 'key')->all())
                ->all();
        }

        return [
            'document' => [
                'plain' => 'ورق عادي',
                'coated' => 'ورق كوشيه',
                'cardstock' => 'ورق مقوّى',
            ],
            'photo' => [
                'glossy' => 'ورق صور لامع',
                'matte' => 'ورق صور مطفي',
            ],
        ];
    }

    public static function orientations(): array
    {
        return ['portrait' => 'طولي', 'landscape' => 'عرضي'];
    }

    public static function sideTypes(): array
    {
        return ['single' => 'وجه واحد', 'double' => 'وجهين'];
    }
}


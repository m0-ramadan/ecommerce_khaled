<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationeryPrice extends Model
{
    use HasFactory;

    protected $table = 'stationery_prices';

    protected $fillable = [
        'service_type',
        'paper_size',
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
        return ['A4', 'A3', 'A5', 'A2', 'A1', 'A0'];
    }

    public static function sizeDimensions(): array
    {
        return [
            'A0' => '84.1 × 118.9 cm',
            'A1' => '59.4 × 84.1 cm',
            'A2' => '42.0 × 59.4 cm',
            'A3' => '29.7 × 42.0 cm',
            'A4' => '21.0 × 29.7 cm',
            'A5' => '14.8 × 21.0 cm',
        ];
    }
}


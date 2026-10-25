<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltySetting extends Model
{
    protected $fillable = [
        'is_active',
        'earn_mode',
        'earn_rate',
        'points_per_order',
        'redeem_rate',
        'min_redeem_points',
        'max_redeem_percent',
        'points_expiry_days',
        'terms',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'earn_mode' => 'string',
        'earn_rate' => 'decimal:2',
        'points_per_order' => 'integer',
        'redeem_rate' => 'decimal:2',
        'min_redeem_points' => 'integer',
        'max_redeem_percent' => 'decimal:2',
        'points_expiry_days' => 'integer',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'is_active' => true,
            'earn_mode' => 'amount',
            'earn_rate' => 1,
            'points_per_order' => 0,
            'redeem_rate' => 20,
            'min_redeem_points' => 100,
            'max_redeem_percent' => 50,
            'points_expiry_days' => 365,
        ]);
    }

    public function pointsToSar(float $points): float
    {
        $rate = (float) $this->redeem_rate;

        return $rate > 0 ? round($points / $rate, 2) : 0.0;
    }

    public function sarToPoints(float $amount): int
    {
        $rate = (float) $this->earn_rate;

        return $rate > 0 ? (int) floor($amount * $rate) : 0;
    }
}

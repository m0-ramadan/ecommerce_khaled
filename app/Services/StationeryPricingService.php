<?php

namespace App\Services;

class StationeryPricingService
{
    public const LARGE_FORMAT_INCLUDED_SHEETS = 100;

    public function pagePrice(float $basePrice, string $paperSize, string $paperType): float
    {
        $price = match (strtoupper($paperSize)) {
            'A3' => $basePrice * 1.75,
            'A5' => $basePrice * 0.75,
            default => $basePrice,
        };

        if ($paperType === 'cardstock') {
            $price *= 1.20;
        }

        return round(max(0.05, $price), 2);
    }

    public function total(float $basePrice, string $paperSize, string $paperType, int $sheets): float
    {
        $size = strtoupper($paperSize);
        $pagePrice = $this->pagePrice($basePrice, $size, $paperType);

        if (in_array($size, ['A1', 'A2'], true)) {
            $extraSheets = max(0, $sheets - self::LARGE_FORMAT_INCLUDED_SHEETS);
            $minimumTotal = $size === 'A1' ? 500.0 : 400.0;

            return round($minimumTotal + ($extraSheets * $pagePrice), 2);
        }

        return round($sheets * $pagePrice, 2);
    }
}

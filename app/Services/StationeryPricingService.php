<?php

namespace App\Services;

class StationeryPricingService
{
    /**
     * عدد الورق المشمول في السعر الأدنى للمقاسات الكبيرة A1 / A2.
     */
    public const LARGE_FORMAT_INCLUDED_SHEETS = 100;

    /**
     * السعر الأدنى لأول 100 ورقة للمقاسات الكبيرة.
     * A2: 400 وجه واحد / 750 وجهين — A1: 500 وجه واحد / 900 وجهين.
     * وتُضاف 100 ريال لأي حالة ملونة.
     */
    public function largeFormatMinimum(string $paperSize, string $sideType = 'single', string $colorType = 'bw'): float
    {
        $size = strtoupper($paperSize);

        $minimums = [
            'A2' => [
                'single' => 400.0,
                'double' => 750.0,
            ],
            'A1' => [
                'single' => 500.0,
                'double' => 900.0,
            ],
        ];

        $base = $minimums[$size][$sideType === 'double' ? 'double' : 'single'] ?? 0.0;

        if ($colorType === 'color') {
            $base += 100.0;
        }

        return $base;
    }

    /**
     * سعر الورقة الواحدة.
     * جميع الأسعار تُدار يدوياً من الداشبورد لكل مقاس ونوع ورق، بدون مضاعفات.
     */
    public function pagePrice(float $basePrice, string $paperSize, string $paperType): float
    {
        return round(max(0.05, $basePrice), 2);
    }

    /**
     * إجمالي تكلفة الطباعة لعدد من الورق.
     * - A1 / A2: سعر أدنى ثابت لأول 100 ورقة، ثم سعر كل ورقة زيادة من الداشبورد.
     * - A3 / A4 / A5: عدد الورق × السعر اليدوي للمقاس.
     */
    public function total(
        float $basePrice,
        string $paperSize,
        string $paperType,
        int $sheets,
        string $colorType = 'bw',
        string $sideType = 'single'
    ): float {
        $size = strtoupper($paperSize);

        if (in_array($size, ['A1', 'A2'], true)) {
            $minimumTotal = $this->largeFormatMinimum($size, $sideType, $colorType);
            $extraSheets = max(0, $sheets - self::LARGE_FORMAT_INCLUDED_SHEETS);

            return round($minimumTotal + ($extraSheets * $this->pagePrice($basePrice, $size, $paperType)), 2);
        }

        return round($sheets * $this->pagePrice($basePrice, $size, $paperType), 2);
    }
}

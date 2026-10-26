<?php

namespace App\Services;

use App\Models\StationeryLargeFormatRule;

class StationeryPricingService
{
    /**
     * القيمة الافتراضية لعدد الورق المشمول في سعر البداية
     * (تُستخدم فقط لو جدول إعدادات المقاسات الكبيرة غير موجود).
     */
    public const LARGE_FORMAT_INCLUDED_SHEETS = 100;

    /**
     * سعر البداية للمقاسات الكبيرة.
     * القيم بقت تُدار من الداشبورد (stationery_large_format_rules)،
     * والثوابت دي مجرد fallback لو الجدول لسه مش موجود.
     */
    protected array $fallbackMinimums = [
        'A2' => [
            'single' => ['bw' => 400.0, 'color' => 500.0],
            'double' => ['bw' => 750.0, 'color' => 850.0],
        ],
        'A1' => [
            'single' => ['bw' => 500.0, 'color' => 600.0],
            'double' => ['bw' => 900.0, 'color' => 1000.0],
        ],
    ];

    /**
     * سعر البداية حسب المقاس والوجه واللون.
     */
    public function largeFormatMinimum(string $paperSize, string $sideType = 'single', string $colorType = 'bw'): float
    {
        $size = strtoupper($paperSize);

        $rule = StationeryLargeFormatRule::forSize($size);
        if ($rule) {
            return $rule->startingPrice($sideType, $colorType);
        }

        $side = $sideType === 'double' ? 'double' : 'single';
        $color = $colorType === 'color' ? 'color' : 'bw';

        return (float) ($this->fallbackMinimums[$size][$side][$color] ?? 0.0);
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
     * - A1 / A2: حسب إعدادات الداشبورد (سعر بداية + عدد ورق مشمول، أو سعر × عدد).
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
        $pricePerSheet = $this->pagePrice($basePrice, $size, $paperType);

        if (in_array($size, ['A1', 'A2'], true)) {
            $rule = StationeryLargeFormatRule::forSize($size);

            if ($rule) {
                return $rule->total($pricePerSheet, $sheets, $colorType, $sideType);
            }

            $extraSheets = max(0, $sheets - self::LARGE_FORMAT_INCLUDED_SHEETS);

            return round(
                $this->largeFormatMinimum($size, $sideType, $colorType) + ($extraSheets * $pricePerSheet),
                2
            );
        }

        return round($sheets * $pricePerSheet, 2);
    }
}

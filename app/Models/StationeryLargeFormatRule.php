<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class StationeryLargeFormatRule extends Model
{
    protected $table = 'stationery_large_format_rules';

    protected $fillable = [
        'paper_size',
        'is_minimum_enabled',
        'included_sheets',
        'single_bw_price',
        'single_color_price',
        'double_bw_price',
        'double_color_price',
    ];

    protected $casts = [
        'is_minimum_enabled' => 'boolean',
        'included_sheets' => 'integer',
        'single_bw_price' => 'decimal:2',
        'single_color_price' => 'decimal:2',
        'double_bw_price' => 'decimal:2',
        'double_color_price' => 'decimal:2',
    ];

    /**
     * المقاسات الكبيرة المدعومة.
     */
    public static function sizes(): array
    {
        return ['A1', 'A2'];
    }

    /**
     * كاش داخلي للطلب الواحد بدل ما نضرب الداتابيز لكل ملف.
     *
     * @var array<string, self>|null
     */
    protected static ?array $requestCache = null;

    /**
     * كل الإعدادات مفهرسة بالمقاس.
     *
     * @return array<string, self>
     */
    public static function allKeyed(): array
    {
        if (self::$requestCache !== null) {
            return self::$requestCache;
        }

        if (! Schema::hasTable('stationery_large_format_rules')) {
            return self::$requestCache = [];
        }

        return self::$requestCache = self::all()
            ->keyBy(fn (self $rule) => strtoupper($rule->paper_size))
            ->all();
    }

    public static function forSize(string $paperSize): ?self
    {
        return self::allKeyed()[strtoupper($paperSize)] ?? null;
    }

    public static function flushCache(): void
    {
        self::$requestCache = null;
    }

    /**
     * سعر البداية حسب الوجه واللون.
     */
    public function startingPrice(string $sideType = 'single', string $colorType = 'bw'): float
    {
        $isDouble = $sideType === 'double';
        $isColor = $colorType === 'color';

        $value = match (true) {
            $isDouble && $isColor => $this->double_color_price,
            $isDouble => $this->double_bw_price,
            $isColor => $this->single_color_price,
            default => $this->single_bw_price,
        };

        return round((float) $value, 2);
    }

    /**
     * إجمالي تكلفة الطباعة لهذا المقاس.
     * - لو الحد الأدنى مفعّل: سعر البداية + (الورق الزيادة × سعر الورقة).
     * - لو ملغي: عدد الورق × سعر الورقة.
     */
    public function total(float $pricePerSheet, int $sheets, string $colorType = 'bw', string $sideType = 'single'): float
    {
        $sheets = max(0, $sheets);

        if (! $this->is_minimum_enabled) {
            return round($sheets * $pricePerSheet, 2);
        }

        $included = max(0, (int) $this->included_sheets);
        $extraSheets = max(0, $sheets - $included);

        return round($this->startingPrice($sideType, $colorType) + ($extraSheets * $pricePerSheet), 2);
    }

    /**
     * الشكل اللي بيتبعت للـ API / الفرونت.
     */
    public function toPricingRule(): array
    {
        return [
            'minimum_enabled' => (bool) $this->is_minimum_enabled,
            'included_sheets' => (int) $this->included_sheets,
            'prices' => [
                'single' => [
                    'bw' => (float) $this->single_bw_price,
                    'color' => (float) $this->single_color_price,
                ],
                'double' => [
                    'bw' => (float) $this->double_bw_price,
                    'color' => (float) $this->double_color_price,
                ],
            ],
        ];
    }
}

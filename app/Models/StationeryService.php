<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationeryService extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'name', 'price', 'available_sizes', 'is_active', 'sort_order'];

    protected $casts = [
        'price' => 'decimal:2',
        'available_sizes' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * كل المقاسات المتاحة في المتجر.
     */
    public static function supportedSizes(): array
    {
        return StationeryPrice::defaultSizes();
    }

    /**
     * هل الخدمة متاحة لمقاس الورق ده؟
     * null = متاحة لكل المقاسات، [] = مش متاحة لأي مقاس.
     */
    public function isAvailableForSize(?string $paperSize): bool
    {
        $sizes = $this->available_sizes;

        if (! is_array($sizes)) {
            return true;
        }

        if ($sizes === []) {
            return false;
        }

        $normalized = array_map(
            fn ($size) => strtoupper(trim((string) $size)),
            $sizes
        );

        return in_array(strtoupper((string) $paperSize), $normalized, true);
    }
}

<?php

namespace App\Http\Resources\Website;

use App\Models\Favorite;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $lowestPrice = $this->sizes
            ->flatMap(function ($size) {
                return $size->productTiers;
            })->min('price_per_unit');

        return [
            // ================== Basic Info ==================
            'id'                => $this->id,
            'name'              => $this->name,
            'slug'              => $this->slug,
            'description'       => $this->description,
            'price'             => $this->price,
            'price_text'        => $this->price_text,
            'final_price'       => $this->final_price,
            'has_discount'      => (bool) $this->has_discount,
            'includes_tax'      => (bool) $this->includes_tax,
            'includes_shipping' => (bool) $this->includes_shipping,
            'stock'             => (int) $this->stock,
            'status_id'         => (int) $this->status_id,
            'is_active'         => $this->status_id == 1,

            'lowest_price'      =>  $lowestPrice ?? rand(5, 15),

            // ================== Ads ==================
            'text_ads'          => ProductTextAdResource::collection($this->adsText),
            // ================== Image ==================
            'image' => $this->primaryImage
                ? get_user_image($this->primaryImage->path)
                : url(config('app.default_product_image')),

            // ================== Rating ==================
            'average_rating' => round($this->average_rating, 1),
            'total_reviews'  => $this->reviews_count ?? $this->reviews->count(),

            // ================== Favorite ==================
            'is_favorite' => auth()->check()
                ? Favorite::where('user_id', auth()->id())
                ->where('product_id', $this->id)
                ->exists()
                : false,

            // ================== Relations ==================
            'category' => $this->category
                ? new CategoryResource($this->category)
                : null,

            'discount' => $this->discount
                ? new DiscountResource($this->discount)
                : null,

            // ================== Colors ==================
            'colors' => $this->colors?->map(function ($color) {
                return [
                    'id'               => $color->id,
                    'name'             => $color->name,
                    'hex_code'         => $color->hex_code,
                    'image'            => $color->image ? get_user_image($color->image) : null,
                    'additional_price' => $color->pivot->additional_price ?? 0,
                ];
            }),

            // ================== Delivery Time ==================
            // المدة تُحدد من نوع الإنتاج في الداشبورد وتبدأ من تاريخ تعميد التصميم.
            'production_type' => $this->production_delivery['production_type'],
            'production_type_label' => $this->production_delivery['label'],
            'delivery_time' => [
                'from_days' => $this->production_delivery['from'],
                'to_days'   => $this->production_delivery['to'],
                'estimated' => $this->production_delivery['from'] . ' - ' . $this->production_delivery['to'] . ' أيام عمل',
                'note'      => $this->production_delivery['note'],
                'starts_from' => 'تاريخ تعميد التصميم',
                'production_type' => $this->production_delivery['production_type'],
            ],

            // ================== Warranty ==================
            'warranty' => $this->warranty ? [
                'months'       => $this->warranty->months,
                'description'  => $this->warranty->description,
                'display_text' => $this->warranty->months . ' أشهر ضمان',
            ] : null,

            // ================== Features ==================
            'features' => FeatureResource::collection($this->features),

            // ================== Reviews ==================
            'reviews' => ReviewResource::collection($this->reviews),

            // ================== Sizes & Tiers ==================
            'sizes' => $this->sizes?->map(function ($size) {
                return [
                    'id'   => $size->id,
                    'name' => $size->name,
                    'tiers' => $size->productTiers->map(function ($tier) {
                        return [
                            'id'             => $tier->id,
                            'quantity'       => $tier->quantity,
                            'price_per_unit' => $tier->price_per_unit,
                            'total_price'    => $tier->quantity * $tier->price_per_unit,
                        ];
                    })->values(),
                ];
            }),


            // ================== Offers ==================
            'offers' => OfferResource::collection($this->offers),

            // ================== Materials ==================
            'materials' => $this->materials?->map(function ($material) {
                return [
                    'id'               => $material->id,
                    'name'             => $material->name,
                    'description'      => $material->description,
                    'quantity'         => $material->pivot->quantity,
                    'unit'             => $material->pivot->unit,
                    'additional_price' => $material->pivot->additional_price ?? 0,
                ];
            }),

            // ================== Images ==================
            'images' => $this->images
                ->sortBy('order')
                ->values()
                ->map(function ($image) {
                    return [
                        'id'         => $image->id,
                        'path'       => get_user_image($image->path),
                        'alt'        => $image->alt,
                        'type'       => $image->type,
                        'order'      => $image->order,
                        'is_primary' => (bool) $image->is_primary,
                        'is_active'  => (bool) $image->is_active,
                    ];
                }),
// ================== Options (TREE STRUCTURE) ==================
'options' => $this->options
    ->whereNull('depends_on_option_id')
    ->reject(fn ($option) => $this->isHiddenOptionName($option->option_name))
    ->groupBy('option_name')
    ->map(function ($group) {
        return [
            'name' => $group->first()->option_name,
            'items' => $group->map(function ($option) {
                return $this->formatOptionTree($option);
            })->values(),
        ];
    })
    ->values(),


            // ================== Printing Methods ==================
            'printing_methods' => $this->printingMethods?->map(function ($method) {
                return [
                    'id'          => $method->id,
                    'name'        => $method->name,
                    'description' => $method->description,
                    'base_price'  => $method->base_price,
                ];
            }),

            // ================== Print Locations ==================
            'print_locations' => $this->printLocations?->map(function ($location) {
                return [
                    'id'   => $location->id,
                    'name' => $location->name,
                    'type' => $location->type,
                    'additional_price' => $location->additional_price ?? 0 + ($location->pivot->additional_price ?? $location->additional_price ?? 0),
                ];
            }),

            // ================== Dates ==================
            'created_at'       => $this->created_at?->format('Y-m-d H:i'),
            'updated_at'       => $this->updated_at?->format('Y-m-d H:i'),
            'human_created_at' => $this->created_at?->diffForHumans(),
            'human_updated_at' => $this->updated_at?->diffForHumans(),

            // ================== Meta ==================
            'meta' => [
                'has_colors'        => $this->colors->count() > 0,
                'has_sizes'         => $this->sizes->count() > 0,
                'has_materials'     => $this->materials->count() > 0,
                'has_printing'      => $this->printingMethods->count() > 0,
                'has_warranty'      => !is_null($this->warranty),
                'has_delivery_time' => true,
                'in_stock'          => $this->stock > 0,
                'stock_status'      => $this->stock > 0 ? 'متوفر' : 'نفذت الكمية',
                'stock_class'       => $this->stock > 0 ? 'in-stock' : 'out-of-stock',
            ],
        ];
    }private function formatOptionTree($option)
{
    $keywords = ['رفع', 'ملف', 'صورة'];
    $hasFile = collect($keywords)->contains(
        fn ($word) => str_contains($option->option_value, $word)
    );

    $tiers = method_exists($option, 'getAvailableQuantityTiers')
        ? $option->getAvailableQuantityTiers()
        : collect();

    return [
        'id' => $option->id,
        'name' => $option->option_name,
        'value' => $option->option_value,
        'is_required' => (bool) $option->is_required,
        'has_file' => $hasFile,
        'base_price' => (float) $option->additional_price,

        'tiers' => $tiers->map(function ($tier) {
            return [
                'id' => $tier->id,
                'quantity' => $tier->quantity,
                'price_per_unit' => (float) $tier->price_per_unit,
                'total_price' => (float) ($tier->quantity * $tier->price_per_unit),
            ];
        })->values(),

        'has_multiple_prices' => $tiers->isNotEmpty(),

        'children' => $option->dependentOptions
            ->reject(fn ($child) => $this->isHiddenOptionName($child->option_name))
            ->map(function ($child) {
                return $this->formatOptionTree($child);
            })->values(),
    ];
}

private function isHiddenOptionName(?string $name): bool
{
    $name = (string) $name;

    return str_contains($name, 'تصوير عينة')
        || str_contains($name, 'عينة قبل طباعة')
        || str_contains($name, 'مدة التنفيذ للمنتج')
        || str_contains($name, 'مدة تنفيذ المنتج');
}
}

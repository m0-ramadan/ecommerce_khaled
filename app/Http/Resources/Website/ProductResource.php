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
            'slug'              => $this->slug ?? Str::slug($this->name),
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
            'colors' => $this->colors->map(function ($color) {
                return [
                    'id'               => $color->id,
                    'name'             => $color->name,
                    'hex_code'         => $color->hex_code,
                    'image'            => $color->image ? get_user_image($color->image) : null,
                    'additional_price' => $color->pivot->additional_price ?? 0,
                ];
            }),

            // ================== Delivery Time ==================
            'delivery_time' => $this->deliveryTime ? [
                'from_days' => $this->deliveryTime->from_days,
                'to_days'   => $this->deliveryTime->to_days,
                'estimated' => $this->deliveryTime->from_days . ' - ' . $this->deliveryTime->to_days . ' أيام',
            ] : null,

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
            'sizes' => $this->sizes->map(function ($size) {
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

            // ================== Pricing Tiers ==================
            // 'pricing_tiers' => $this->pricingTiers->map(function ($tier) {
            //     return [
            //         'quantity'             => $tier->quantity,
            //         'price'                => $tier->price,
            //         'discount_percentage'  => $tier->discount_percentage,
            //     ];
            // }),

            // ================== Offers ==================
            'offers' => OfferResource::collection($this->offers),

            // ================== Materials ==================
            'materials' => $this->materials->map(function ($material) {
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

            // ================== Options ==================
            'options' => $this->options->map(function ($option) {
                $keywords = ['رفع', 'ملف', 'صورة'];
                $hasFile = collect($keywords)->contains(fn($word) => str_contains($option->option_value, $word));
                return [
                    'id'               => $option->id,
                    'option_name'      => $option->option_name,
                    'option_value'     => $option->option_value,
                    'additional_price' => $option->additional_price,
                    'is_required'      => (bool) $option->is_required,
                    'file'             => $hasFile ? 1 : 0,
                ];
            }),


            // ================== Printing Methods ==================
            'printing_methods' => $this->printingMethods->map(function ($method) {
                return [
                    'id'          => $method->id,
                    'name'        => $method->name,
                    'description' => $method->description,
                    'base_price'  => $method->base_price,
                ];
            }),

            // ================== Print Locations ==================
            'print_locations' => $this->printLocations->map(function ($location) {
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
                'has_delivery_time' => !is_null($this->deliveryTime),
                'in_stock'          => $this->stock > 0,
                'stock_status'      => $this->stock > 0 ? 'متوفر' : 'نفذت الكمية',
                'stock_class'       => $this->stock > 0 ? 'in-stock' : 'out-of-stock',
            ],
        ];
    }
}

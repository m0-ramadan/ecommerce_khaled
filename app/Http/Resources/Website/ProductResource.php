<?php

namespace App\Http\Resources\Website;

use Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'slug'              => $this->slug ?? Str::slug($this->name),
            'description'       => $this->description,
            'price'             => $this->price,
            'final_price'       => $this->final_price,
            'has_discount'      => $this->has_discount,
            'includes_tax'      => $this->includes_tax,
            'includes_shipping' => $this->includes_shipping,
            'stock'             => $this->stock,
            'average_rating'    => round($this->average_rating, 1),

            // Relationships
            'category'     => new CategoryResource($this->whenLoaded('category')),
            'discount'     => new DiscountResource($this->whenLoaded('discount')),
            'colors'       => ColorResource::collection($this->whenLoaded('colors')),
            'delivery_time'=> new DeliveryTimeResource($this->whenLoaded('deliveryTime')),
            'warranty'     => new WarrantyResource($this->whenLoaded('warranty')),
            'features'     => FeatureResource::collection($this->whenLoaded('features')),
            'reviews'      => ReviewResource::collection($this->whenLoaded('reviews')),
            'sizes'        => SizeResource::collection($this->whenLoaded('sizes')),
            'offers'       => OfferResource::collection($this->whenLoaded('offers')),

            'created_at'   => $this->created_at?->format('Y-m-d'),
            'updated_at'   => $this->updated_at?->format('Y-m-d'),
        ];
    }
}
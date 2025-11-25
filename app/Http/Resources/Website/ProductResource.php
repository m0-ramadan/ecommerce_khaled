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
            'image'             => $this->image ? get_user_image($this->image) : "https://eg-rv.homzmart.net/catalog/product/J/O/JOUFURH08142090-1-Navyblue.jpg",
            'average_rating'    => round($this->average_rating, 1),
            'is_favorite' => auth()->check()
                ? Favorite::where('user_id', auth()->id())
                ->where('product_id', $this->id)
                ->exists()
                : false,
            // Relationships
            'category'      => new CategoryResource($this->category),
            'discount'      => new DiscountResource($this->discount),
            'colors'        => ColorResource::collection($this->colors),
            'delivery_time' => new DeliveryTimeResource($this->deliveryTime),
            'warranty'      => new WarrantyResource($this->warranty),
            'features'      => FeatureResource::collection($this->features),
            'reviews'       => ReviewResource::collection($this->reviews),
            'sizes'         => SizeResource::collection($this->sizes),

            'offers'        => OfferResource::collection($this->offers),
            'materials'     => MaterialResource::collection($this->materials),
            'images'        => ImageResource::collection($this->images),


            // 'created_at'   => $this->created_at?->format('Y-m-d'),
            // 'updated_at'   => $this->updated_at?->format('Y-m-d'),
        ];
    }
}

<?php

namespace App\Http\Resources\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'price_text' => $this->price_text,
            'final_price' => $this->final_price,
            'lowest_price' => $this->lowest_price,
            'stock' => (int) $this->stock,
            'image' => $this->primaryImage
                ? get_user_image($this->primaryImage->path)
                : url(config('app.default_product_image')),
            'average_rating' => round((float) ($this->reviews_avg_rating ?? 0), 1),
            'total_reviews' => (int) ($this->reviews_count ?? 0),
            'reviews' => [],
            'discount' => $this->discount ? new DiscountResource($this->discount) : null,
            'text_ads' => ProductTextAdResource::collection($this->whenLoaded('adsText')),
            'is_summary' => true,
        ];
    }
}

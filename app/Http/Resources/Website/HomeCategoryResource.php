<?php

namespace App\Http\Resources\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->image ? get_user_image($this->image) : null,
            'category_banners' => BannerItemResource::collection($this->whenLoaded('categoryBanners')),
            'products' => HomeProductResource::collection($this->whenLoaded('products')),
        ];
    }
}

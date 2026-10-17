<?php

namespace App\Http\Resources\Website;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'type'          => new BannerTypeResource($this->whenLoaded('bannerType')),
            'order'         => $this->section_order,
            'is_active'     => $this->is_active,

            // Settings
            'slider_settings' => new SliderSettingsResource($this->whenLoaded('sliderSettings')),
            'grid_layout'     => new GridLayoutResource($this->whenLoaded('gridLayout')),

            // Items (only return items with valid existing images)
            'items' => BannerItemResource::collection(
                $this->whenLoaded('items', function () {
                    return $this->items->filter(function ($item) {
                        if ($item->is_active === false || $item->is_active === 0) return false;
                        $hasImg = $item->image_url && (str_starts_with($item->image_url, 'http') || \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image_url));
                        $hasMob = $item->mobile_image_url && (str_starts_with($item->mobile_image_url, 'http') || \Illuminate\Support\Facades\Storage::disk('public')->exists($item->mobile_image_url));
                        return $hasImg || $hasMob;
                    });
                })
            ),

            'dates' => [
                'start' => $this->start_date,
                'end'   => $this->end_date,
            ]
        ];
    }
}
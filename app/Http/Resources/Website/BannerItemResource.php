<?php

namespace App\Http\Resources\Website;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'order'         => $this->item_order,

            // 🟢 روابط الصور باستخدام الهيلبر
            'image'         => $this->image_url ? get_user_image($this->image_url) : null,
            'mobile_image'  => $this->mobile_image_url ? get_user_image($this->mobile_image_url) : null,
            'alt'           => $this->image_alt,

            // روابط
            'link_url'      => $this->link_url,
            'link_target'   => $this->link_target,
            'is_link_active'=> $this->is_link_active,

            // منتجات/كاتيجوري
            'product_id'    => $this->product_id,
            'category_id'   => $this->category_id,

            // تاج
            'tag' => [
                'text'       => $this->tag_text,
                'color'      => $this->tag_color,
                'background' => $this->tag_bg_color,
            ],

            'is_active'     => $this->is_active,
        ];
    }
}
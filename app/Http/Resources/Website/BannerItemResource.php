<?php

namespace App\Http\Resources\Website;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resolveImage = function (?string $path) {
            if (!$path) {
                return null;
            }
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                return asset('storage/' . $path);
            }
            return null;
        };

        $desktopImage = $resolveImage($this->image_url);
        $mobileImage = $resolveImage($this->mobile_image_url);

        // Determine if request explicitly asks for mobile or comes from mobile device
        $isMobileRequest = $request->input('device') === 'mobile'
            || $request->header('X-Device-Type') === 'mobile'
            || ($request->header('User-Agent') && preg_match('/Mobile|Android|iPhone|iPad/i', $request->header('User-Agent')));

        // Primary image field: adaptive based on device context
        $primaryImage = $isMobileRequest
            ? ($mobileImage ?? $desktopImage)
            : ($desktopImage ?? $mobileImage);

        return [
            'id'            => $this->id,
            'order'         => $this->item_order,

            // 🟢 روابط الصور باستخدام الهيلبر
            'image'         => $primaryImage,
            'desktop_image' => $desktopImage,
            'mobile_image'  => $mobileImage,
            'alt'           => $this->image_alt,

            // روابط
            'url'           => $this->link_url,
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
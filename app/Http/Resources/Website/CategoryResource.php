<?php


namespace App\Http\Resources\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * صور افتراضية قديمة كانت متخزنة في قاعدة البيانات كـ DEFAULT
     * لأعمدة الصور، وبنعتبرها "مفيش صورة" عشان الصورة الحقيقية تظهر.
     */
    public const PLACEHOLDER_IMAGES = [
        'https://i.ibb.co/20wDxXg9/3.png',
        'https://i.ibb.co/rffyqbmk/1.png',
    ];

    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'parent'      => $this->whenLoaded('parent', fn() => new self($this->parent)),
            'children'    => self::collection($this->children),
            'order'       => $this->order,
            'image'       => self::resolveImage($this->image),
            'sub_image'   => self::resolveImage($this->sub_image) ?? self::resolveImage($this->image),
            'is_parent'   => $this->isParent(),
            'category_banners' => BannerItemResource::collection($this->categoryBanners),
        ];
    }

    public static function resolveImage(?string $path): ?string
    {
        if (blank($path) || in_array($path, self::PLACEHOLDER_IMAGES, true)) {
            return null;
        }

        return get_user_image($path);
    }
}

<?php


namespace App\Http\Resources\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
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
            'image'       => $this->image ? get_user_image($this->image) : null ,
            'sub_image'       => $this->sub_image ? get_user_image($this->sub_image) : null ,
            'is_parent'   => $this->isParent(),
        ];
    }
}
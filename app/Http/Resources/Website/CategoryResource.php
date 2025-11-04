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
            'children'    => self::collection($this->whenLoaded('children')),
            'order'       => $this->order,
            'is_parent'   => $this->isParent(),
        ];
    }
}
<?php

namespace App\Http\Resources;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewimgResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
 public function toArray($request)
    {
        $attributes = parent::toArray($request);
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, \App::getLocale());
        }
        if ($this->image) {
            $attributes['image'] = asset('/') .'public/'. $this->img;
        }
        return $attributes;
    }
}

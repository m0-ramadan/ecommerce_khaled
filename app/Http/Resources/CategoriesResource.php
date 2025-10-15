<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
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
        if ($this->subCategories->count() == 0) {
            $attributes["hasSubCategories"] = 0;
        } else {
            $attributes["hasSubCategories"] = 1;
        }
        if ($this->image) {
            $attributes['image'] = asset('/') . 'public/' . $this->image_mop;
        }
        return $attributes;
    }
}

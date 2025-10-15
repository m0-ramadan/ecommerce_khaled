<?php

namespace App\Http\Resources;

use App\Models\ListCards;

use Illuminate\Http\Resources\Json\JsonResource;

class ListCardsResource extends JsonResource
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

            $attributes['image'] = asset('public/'.$attributes['image']);



        return $attributes;
    }
}

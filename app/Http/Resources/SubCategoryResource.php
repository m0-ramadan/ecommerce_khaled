<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'ID'=>$this->id,
            'Name'=>$this->name,
            'Details'=>$this->details,
            'Image'=>asset('/').'public/'.$this->image ?? '',
            'Category_id'=>$this->category_id,
        ];

        $attributes = parent::toArray($request);
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, \App::getLocale());
        }
        if ($this->image) {
            $attributes['image'] = asset('/') .'public/'. $this->image;
        }
        return $attributes;
    }
}

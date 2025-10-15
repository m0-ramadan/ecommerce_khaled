<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
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
            // dd($this->id);
            $attributes[$field] = $this->getTranslation($field, \App::getLocale());
        }
        if($this->image)
        {
            $attributes['image'] = [] ;
            foreach(explode(',', $this->image) as  $img){
                $attributes['image'][] = asset('/') . 'public/'. $img;
            }
        }else{
            $attributes['image'] = [] ;
        }
        return $attributes;
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DistrictsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         foreach ($this->getTranslatableAttributes() as $field) {
            $data[$field] = $this->getTranslation($field, \App::getLocale());
        }
        
        $data = [
            'id'=>$this->id,
            'name'=>$this->name,
            'city_id'=>$this->cities_id,
        ];

        return $data;
    }
}

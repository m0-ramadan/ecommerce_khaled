<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqsResource extends JsonResource
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
            'ID'=>$this->id,
            'questions'=>$this->questions,
        ];


        return $data;
    }
}

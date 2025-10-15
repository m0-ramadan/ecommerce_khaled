<?php

namespace App\Http\Resources;

use App\Models\Client;
use App\Models\Image;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            $attributes[$field] = $field;
        }
        $attributes['image'] = asset('public/'.$attributes['image']);
    return $attributes;
    }
}

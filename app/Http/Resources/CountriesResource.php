<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Resources\Json\JsonResource;

class CountriesResource extends JsonResource
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
            $data[$field] = $this->getTranslation($field, App::getLocale());
        }
        $data = [
            'ID'=>$this->id,
            'Name'=>$this->name,
            'country_ref_code' => $this->country_ref_code
        ];


        return $data;
    }
}

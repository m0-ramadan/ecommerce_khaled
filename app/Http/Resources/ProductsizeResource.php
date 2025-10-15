<?php

namespace App\Http\Resources;
use App\Models\ProductFeatures;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsizeResource extends JsonResource
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
            'name'=>$this->getTranslation('name',\App::getLocale())        ];
        return $data;

        
    }
}
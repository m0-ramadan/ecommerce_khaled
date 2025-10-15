<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class SettingResource extends JsonResource
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
        if ($this->offer_image) {
            $attributes['offer_image'] = asset('/') . 'public/' . $this->offer_image;
        }
        
        return $attributes; 
        // $data = [
        //     'key_offer'=>$this->key_offer,
        //     'promo_code_name'=>$this->promo_code_name,
        //     'offer_image'=> asset('/').'public/'.$this->offer_image ?? '',
        // ];
        // return $data;
    }
}

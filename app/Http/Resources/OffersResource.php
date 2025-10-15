<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OffersResource extends JsonResource
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
            'Title'=>$this->title,
            'title2'=>$this->title2,
            'title3'=>$this->title3,
            'title4'=>$this->title4,
            'title5'=>$this->title5,
            'Content'=>$this->content,
            'Image'=>asset('/').('public/'.$this->image) ?? '',
            'Discount'=>$this->discount,
            'Details'=>$this->details,
        ];

        return $data;
    }
}

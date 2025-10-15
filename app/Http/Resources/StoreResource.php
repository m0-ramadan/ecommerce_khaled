<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'Phone'=>$this->phone,
            'AboutUS'=>$this->about,
            'FaceBookLink'=>$this->facebook,
            'InstagramLink'=>$this->instagram,
            'twitterLink'=>$this->twitter,
            'Judgments'=>$this->judgments,
            'Replacement'=>$this->replacement,
            'Image'=>asset('/').'public/'.$this->image ?? '',
        ];

        return $data;
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this->image_mop);
        $data = [
            'ID'=>$this->id,
            'Image'=> asset('/').'public/'.$this->image_mop ?? '',
        ];
        return $data;
    }
}

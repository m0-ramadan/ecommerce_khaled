<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LastBlogResource extends JsonResource
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
            'Content'=>$this->content,
            'Image'=> asset('/').'public/'.$this->image ?? '',
            'Time'=>$this->time ?? '',
            'created_at'=>$this->created_at,
        ];

        return $data;
    }
}

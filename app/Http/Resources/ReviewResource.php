<?php

namespace App\Http\Resources;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
        $images = Image::where('product_id',$this->id)->get();
        foreach ($images as $image)
        {
            $attributes['image'] = asset('public/'.$image->src);
        }
        return $attributes;
    }

}

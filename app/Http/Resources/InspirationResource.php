<?php

namespace App\Http\Resources;
use App\Models\Favorite;
use App\Models\CartItem;
use Illuminate\Http\Resources\Json\JsonResource;

class InspirationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
   
    public function toArray($request)
    {
        return [
            'ID' => $this->id,
            'Image' => asset('/') . 'public/' . $this->image ?? '',
            'url-link' => $this->link_id,
            'product-name' => $this->product ? $this->product->name : null,
            'hasFavorites' => $this->product->Favorites->count() ?? 0,

        ];
    }
}

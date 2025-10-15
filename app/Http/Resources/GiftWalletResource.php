<?php

namespace App\Http\Resources;

use App\Models\ListCards;
 
use Illuminate\Http\Resources\Json\JsonResource;

class GiftWalletResource extends JsonResource
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
             'phone'=>$this->phone,
             'sender_name'=>$this->client->name,
            'price'=>$this->price,
            'Image'=>asset('/').('public/'.$this->image) ?? '',
            'message'=>$this->message,
            'client_id'=>$this->client_id,
            
        ];

        return $data;
    }
}

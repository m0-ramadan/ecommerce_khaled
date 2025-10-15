<?php

namespace App\Http\Resources;
use App\Models\Client;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
          if(auth('api')->user()){
          $myList = Client::where('id',auth('api')->user()->id)->first();
                $img=asset('/') .'public/'.$myList->image;
            }
            else {
                $img="";
            }
        $data = [
            'sales_id'=>1,
            'technicalsupport'=>2,
            'userimg'=>$img,
            'ID'=>$this->id,
            'youtube-link'=>$this->youtube,
            'tiktok-link'=>$this->tiktok,
            'Instagram-link'=>$this->instagram,
            'Twitter-link'=>$this->twitter,
            'Phone'=>$this->phone,
            'Mail'=>$this->mail,
            // 'Image'=> asset('/').'public/'.$this-> ?? '',
            // 'Details'=>$this->details,
            // 'Replacement'=>$this->replacement,
            // 'Judgments'=>$this->judgments,
            
            // 'Image'=>$this->image ?? '',
            // 'Location'=>$this->location,
        ];

        return $data;
    }
}

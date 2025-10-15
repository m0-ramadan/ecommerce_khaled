<?php

namespace App\Http\Resources;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
class ListFavoritesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $product_id=Favorite::select("product_id")->limit(1)->orderBy('id', 'desc')->where('list_id',$this->id)->first();
         if($product_id != null){
             $productid=$product_id->product_id;
             $img_id=Product::select("image")->limit(1)->orderBy('id', 'desc')->where('id',$productid)->first();
               $img= $img_id ? asset('/').'public/'.$img_id->image : null;
         }
         else {$productid=null;$img=null;}
         
        $data = [
            'ID'=>$this->id,
            'client_id'=>$this->client_id,
            'name'=>$this->name,
            'Image'=> $img,
            'created_at'=>$this->created_at,
        ];
        return $data;
    }
}

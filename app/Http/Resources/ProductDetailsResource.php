<?php

namespace App\Http\Resources;

use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Image;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
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
      if($attributes['old_price'] == null){
            $attributes['old_price']= 0;
      }
      if($attributes['sub_category_id'] == null){
            $attributes['sub_category_id'] = 0;
        }
            $attributes['image'] = asset('public/'.$attributes['image']);
            
            
        $images = Image::where('product_id',$this->id)->get();
        foreach ($images as $image)
        {
            $attributes['images'][]= asset('public/'.$image->src);
        }
        
        $user = auth('api')->user();
        if($user)
        {
             
             $cart_id=Cart::select('id')->where('client_id',$user->id)->first();
              
              if($cart_id!=null){
                  $cart_total=CartItem::where('cart_id',$cart_id->id)->count();
             $itemid=CartItem::select('id','smart_type')->where('cart_id',$cart_id->id)->where('product_id',$this->id)->first();
              $itemidsmart=CartItem::select('id')->where('cart_id',$cart_id->id)->where('product_id',$this->id)->get();
              if ($itemid ==null){$attributes["hascart"] =0;$attributes["smart_type"] =4;$attributes["cart_total"] =$cart_total;}
              else{ $attributes["hascart"] = 1;$attributes["smart_type"] =$itemid->smart_type;
                  
                  if (count($itemidsmart)==2){
                      $attributes["smart_type"] =3;
                  }
                  else {
                      $attributes["smart_type"] =$itemid->smart_type;
                  }
                  $attributes["cart_total"] =$cart_total;
              }
            
              }
              else { $attributes["hascart"] =0;$attributes["smart_type"] =4;$attributes["cart_total"] =0;}
              
              
            
          
            if ($this->Favorites->count() == 0){
                $attributes["hasFavorites"] = 0;}else{
                $attributes["hasFavorites"] = 1;
            }
            if ($this->Rosters->count() == 0){
                $attributes["hasRosters"] = 0;}else{
                $attributes["hasRosters"] = 1;
            }
            if ($this->reviews->count() == 0){
                $attributes["hasReviews"] ="0";}
                else{
                foreach ($this->reviews as $review)
                  $resrate=$review->rate;
                    $attributes["hasReviews"] = "$resrate";
                $attributes["ReviewComment"] = $review->comment;
            }
        }
        
        else {
            $attributes["smart_type"] =4;
             $attributes["hascart"] = 0;
             $attributes["hasFavorites"] = 0;
              $attributes["hasRosters"] = 0;
               $attributes["hasReviews"] ="0";
                $attributes["ReviewComment"] = "";
            $attributes["cart_total"] =0;
        }

        return $attributes;
    }
}
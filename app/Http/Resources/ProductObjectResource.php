<?php

namespace App\Http\Resources;

use App\Models\CartItem;
use App\Models\Favorite;
use App\Models\Image;
use App\Models\ProductFeatures;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductObjectResource extends JsonResource
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
        foreach ($this->getTranslatableAttributes() as $field) 
            $attributes[$field] = $this->getTranslation($field, \App::getLocale());
        
        
        if($attributes['old_price']==null){
            
            $attributes['old_price']= 0;
           
            
        }
        
         if($attributes['sub_category_id'] ==null ){
            
           
            $attributes['sub_category_id'] = 0;
           
            
        }
        
         if( $attributes['store_id']   == null){

            $attributes['store_id'] = 0;
            
        }
          $attributes['image'] = asset('public/'.$attributes['image']);
            
        $user = auth('api')->user();
        if($user)
        {
            if ($this->Favorites->count() == 0){
                $attributes["hasFavorites"] = 0;}else{
                $attributes["hasFavorites"] = 1;
            }
            if ($this->Rosters->count() == 0){
                $attributes["hasRosters"] = 0;}else{
                $attributes["hasRosters"] = 1;
            }
            if ($this->reviews->count() == 0){
                $attributes["hasReviews"] = "0";}else{
                foreach ($this->reviews as $review)
                   $resrate=$review->rate;
                    $attributes["hasReviews"] = "$resrate";
                $attributes["ReviewComment"] = $review->comment;
            }
        }
        else {
              $attributes["hasFavorites"] = 0;
              $attributes["hasRosters"] = 0;
               $attributes["hasReviews"] ="0";
                $attributes["ReviewComment"] = "";
        }
                  if ($this->reviews->count() == 0){
                $attributes["hasReviews"] ="0";}
                else{
                foreach ($this->reviews as $review)
                  $resrate=$review->rate;
                    $attributes["hasReviews"] = "$resrate";
                $attributes["ReviewComment"] = $review->comment;
            }

        return $attributes;
    }
}

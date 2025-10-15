<?php

namespace App\Http\Resources;

use App\Models\CartItem;
use App\Models\Favorite;
use App\Models\Image;
use App\Models\ProductFeatures;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
        
        if ($attributes['old_price'] == null) {
             $attributes['old_price'] = 0;
        }

        if ($attributes['sub_category_id'] == null) {
              $attributes['sub_category_id'] = 0;
        }

        if ($attributes['store_id']   == null) {
             $attributes['store_id'] = 0;
        }
        $attributes['image'] = asset('public/' . $attributes['image']);
         $user = auth('api')->user();

        if ($user) {
            if ($this->Favorites->count() == 0) {
                $attributes["hasFavorites"] = 0;
            } else {
                $attributes["hasFavorites"] = 1;
            }
            if ($this->Rosters->count() == 0) {
                $attributes["hasRosters"] = 0;
            } else {
                $attributes["hasRosters"] = 1;
            }
            if ($this->reviews->count() == 0) {
                $attributes["hasReviews"] = "0";
            } else {
                foreach ($this->reviews as $review)
                    $resrate = $review->rate;
                $attributes["hasReviews"] = "$resrate";
                $attributes["ReviewComment"] = $review->comment;
            }
        } else {
            $attributes["hasFavorites"] = 0;
            $attributes["hasRosters"] = 0;
            $attributes["hasReviews"] = "0";
            $attributes["ReviewComment"] = "";
        }
        if ($this->reviews->count() == 0) {
            $attributes["hasReviews"] = "0";
        } else {
            foreach ($this->reviews as $review)
                $resrate = $review->rate;
            $attributes["hasReviews"] = "$resrate";
            $attributes["ReviewComment"] = $review->comment;
        }

        return $attributes;
        
        // $attributes = parent::toArray($request);
    
        // $attributes['old_price'] = $attributes['old_price'] ?? 0;
        // $attributes['sub_category_id'] = $attributes['sub_category_id'] ?? 0;
        // $attributes['store_id'] = $attributes['store_id'] ?? 0;
    
        // $attributes['image'] = isset($attributes['image']) ? asset('public/' . $attributes['image']) : "";
    
        // $user = auth('api')->user();
    
        // if ($user) {
        //     // Use ternary operator for setting hasFavorites and hasRosters
        //     $attributes["hasFavorites"] = property_exists($this, 'Favorites') && $this->Favorites->count() > 0 ? 1 : 0;
        //     $attributes["hasRosters"] = property_exists($this, 'Rosters') && $this->Rosters->count() > 0 ? 1 : 0;
    
        //     if ( property_exists($this, 'reviews') && $this->reviews->count() > 0) {
        //         // Use implode to concatenate all review rates
        //         $attributes["hasReviews"] = property_exists($this, 'reviews') ? implode('', $this->reviews->pluck('rate')->toArray()) : "";
        //         $attributes["ReviewComment"] = property_exists($this, 'reviews') ? $this->reviews->first()->comment : "";
        //     } else {
        //         $attributes["hasReviews"] = "0";
        //         $attributes["ReviewComment"] = "";
        //     }
        // } else {
        //     // Use ternary operator for setting default values
        //     $attributes["hasFavorites"] = 0;
        //     $attributes["hasRosters"] = 0;
        //     $attributes["hasReviews"] = "0";
        //     $attributes["ReviewComment"] = "";
        // }
    
        // return $attributes;
    }
}

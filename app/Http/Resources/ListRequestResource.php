<?php

namespace App\Http\Resources;

use App\Models\ProductFeature;
use Illuminate\Http\Resources\Json\JsonResource;

class ListRequestResource extends JsonResource
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
        // $image = $this->product->image;
        $attributes['image'] = asset('public/' . $this->product->image);
        $attributes['product_name'] = $this->product->name;
        $attributes['product_old_price'] = $this->product->old_price;
        $attributes['product_current_price'] = $this->product->current_price;
        // $attributes['product_quantity'] = $this->product->quantity;
        // $attributes['product_serial_no'] = $this->product->serial_no;
        // $productfeature = ProductFeature::where('product_id', $this->product_id)->get();
        foreach ($this->product->productFeatures as $product_feature) {
            $attributes['product_features'][] = $product_feature->description;
        }
        return $attributes;
    }
}

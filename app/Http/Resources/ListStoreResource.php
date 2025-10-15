<?php

namespace App\Http\Resources;

use App\Models\ProductFeature;
use App\Models\OrderItem;
use Illuminate\Http\Resources\Json\JsonResource;
//order_items
class ListStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data['image'] = asset('public/' . $this->product->image);
        $data['product_id'] = $this->product->id;
        $data['product_name'] = $this->product->name;
        $data['product_old_price'] = $this->product->old_price;
        $data['product_current_price'] = $this->product->current_price;
        $data['product_quantity'] = (int)$this->product->quantity;
        $data['product_quantity_original'] = (int)$this->original_quantity;
        $data['product_serial_no'] = $this->product->serial_no;
        $data['product_quantity_sold'] = $this->order_items_sum_quantity ? (int)$this->order_items_sum_quantity : 0;
        foreach ($this->product->productFeatures as $product_feature) {
            $data['feature_name'][] = $product_feature->name;
            $data['feature_description'][] = $product_feature->description;
        }
        return $data;
    }
}

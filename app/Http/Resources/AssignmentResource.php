<?php

namespace App\Http\Resources;

use App\Models\Contact;
use App\Models\ProductFeature;
use App\Models\OrderItem;
use Illuminate\Http\Resources\Json\JsonResource;
//order_items
class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $contact = Contact::first();
        $total = $this->calcTotal($contact);

        $data['image'] = asset('public/' . $this->product->image);
        $data['product_id'] = $this->product->id;
        $data['product_name'] = $this->product->name;


        $data['product_mainprice'] = $this->product->mainprice;

        $data['tax'] = $this->product->mainprice * ($contact->tax_rate / 100);
        $data['insurance'] = $this->product->mainprice * ($contact->insurance / 100);
        $data['zakat'] = $this->product->mainprice * ($contact->zakat_percentage / 100);

        $data['profit_product'] = ($data['product_mainprice'] + $data['tax'] + $data['insurance'] + $data['zakat']) * ($contact->profit_product / 100);

        $data['visa_percentage'] = $data['profit_product'] * ($contact->visa_percentage / 100);
        $data['advertising_and_development'] = $data['profit_product'] * ($contact->advertising_and_development / 100);

        $data['total'] = $total;
        $data['net_profit'] = ($data['total'] - $this->product->mainprice);
        $data['client'] =  $data['net_profit'] * $this->client->percentage;
        $data['company'] = $data['net_profit'] - $data['client'];

        $data['product_old_price'] = (float)$this->product->old_price;
        $data['product_current_price'] = (float) $this->product->current_price;
        $data['product_quantity'] = $this->product->quantity;
        $data['product_quantity_original'] = (int)$this->original_quantity;
        $data['product_serial_no'] = $this->product->serial_no;
        $data['product_quantity_sold'] = $this->order_items_sum_quantity ? (int)$this->order_items_sum_quantity : 0;

        foreach ($this->product->productFeatures as $product_feature) {
            $data['feature_name'][] = $product_feature->name;
            $data['feature_description'][] = $product_feature->description;
        }
        return $data;
    }

    public function calcTotal(Contact $contact): float
    {
        $total = $this->product->mainprice;

        $total += $this->product->mainprice * ($contact->tax_rate / 100);
        $total += $this->product->mainprice * ($contact->insurance / 100);
        $total += $this->product->mainprice * ($contact->zakat_percentage / 100);
        $total += $total * ($contact->profit_product / 100);

        $total -= $total * ($contact->visa_percentage / 100);
        $total -= $total * ($contact->advertising_and_development / 100);

        return $total;
    }
}

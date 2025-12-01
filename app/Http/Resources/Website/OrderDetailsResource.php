<?php

namespace App\Http\Resources\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
public function toArray($request)
{
    return [
        "id"               => $this->id,
        'order_number'     => $this->order_number,
        'status'           => $this->status,
        'status_label'     => __('order.status.' . $this->status),
        'total_amount'     => $this->total_amount,
        'formatted_total'  => number_format($this->total_amount, 2) . ' ج.م',
        'customer_name'    => $this->customer_name,
        'customer_phone'   => $this->customer_phone,
        'shipping_address' => $this->shipping_address,
        'notes'            => $this->notes,
        'status_payment'   => $this->status_payment,
        'user'             => new UserResource(auth()->user()) ,
        'created_at'       => $this->created_at->translatedFormat('d M Y - h:i A'),
        
        // ============ هنا الإضافات الجديدة =============
        'payment_method_label' => $this->payment_method ?  $this->payment_method : null,
        'full_address'         => $this->address ? new UserAddressResource($this->address) : null,
        // ================================================

        'items' => $this->items->map(fn($item) => [
            'product_name' => $item->product->name,
            'quantity'     => $item->quantity,
            'price'        => $item->total_price,
            'options'      => $item->selected_options,
            'product'      => new ProductResource($item->product),
        ]),
    ];
}

}

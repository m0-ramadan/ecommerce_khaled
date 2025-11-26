<?php

namespace App\Http\Resources\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'order_number' => $this->order_number,
            'status'       => $this->status,
            'status_label' => __('order.status.' . $this->status),
            'total'        => $this->total_amount,
            'formatted_total' => number_format($this->total_amount, 2) . ' ج.م',
            'items_count'  => $this->items->sum('quantity'),
            'created_at'   => $this->created_at->format('Y-m-d H:i'),
            'can_cancel'   => in_array($this->status, ['pending', 'processing']),
        ];
    }
}

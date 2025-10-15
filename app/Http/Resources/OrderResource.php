<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'code_order' => (string)$this->code_order,
            'client_id' => $this->client_id,
            'payment_status' => strtolower(array_search($this->payment_status, Order::PAYMENT_STATUSES)),
            'payment_type' => strtolower(array_search($this->payment_type, Order::PAYMENT_TYPES)),
            'promo_code_id' => (string)$this->promo_code_id,
            'sub_total' =>number_format ($this->sub_total, 2, '.', ''), // number_format((float)$foo, 2, '.', '');
            'delivery_cost' => number_format($this->delivery_cost, 2, '.', ''), // number_format((float)$foo, 2, '.', '');
            'total' => number_format($this->total, 2, '.', ''), // number_format((float)$foo, 2, '.', '');
            // 'status' => strtolower(array_search($this->status, Order::ORDER_STATUSES)),
            'status' => (string)$this->status,
            'address' => (string)$this->address,
            'user_name' => (string)$this->user_name,
            'user_phone' => (string)$this->user_phone,
            'user_email' => (string)$this->user_email,
            'country_ref_code' => (string)$this->country_ref_code,
            'state_id' => (string)$this->state_id,
            'country_id' => (string)$this->country_id,
            'payment_ref_code' => (string)$this->payment_ref_code,
            'state_name' => (string)$this->state_name,
            'rate_status' => (string)$this->rate_status,
            'rate_comment' => (string)$this->rate_comment,
            'delivery_date' => (string)$this->delivery_date,
            'cash_back_amount' => (string)$this->cash_back_amount,
            'card_id' => (string)$this->card_id,
            'amount' => (string)$this->amount,
            'created_at' => (string)$this->created_at,
            'neighborhood' => (string)$this->neighborhood,
            'zip_code' => (string)$this->zip_code,
            'products_count' => $this->products_count,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoCodeResource extends JsonResource
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
            'code'=> (string)$this->code,
            'value'=> (string)$this->value,
            'start_date' => (string)$this->start_date,
            'end'=> (string)$this->end,
            'counts'=> (string)$this->counts,
        ];
    }
}

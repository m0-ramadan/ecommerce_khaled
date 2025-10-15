<?php

namespace App\Http\Resources;

use App\Models\ProductFeature;
use App\Models\Expensimg;
use Illuminate\Http\Resources\Json\JsonResource;
//order_items
class ExpensesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'id' => $this->id,
            'details' => $this->details,
            'total_money' => $this->total_money,
            'file' => $this->file ? 'https://taqiviolet.com/public/' . $this->file : null,
            'created_at' => $this->created_at,
        ];
    }
}

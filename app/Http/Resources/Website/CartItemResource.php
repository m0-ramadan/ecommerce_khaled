<?php

namespace App\Http\Resources\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'product'            => new ProductResource($this->product),
            'size'               => $this->whenLoaded('size', fn() => $this->size?->name),
            'color'              => $this->whenLoaded('color', fn() => [
                'name' => $this->color?->name,
                'hex'  => $this->color?->hex_code,
            ]),
            'printing_method'    => $this->whenLoaded('printingMethod', fn() => $this->printingMethod?->name),
            'print_locations'    => $this->print_locations,
            'embroider_locations'=> $this->embroider_locations,
            'selected_options'   => $this->selected_options,
            'design_service'     => $this->whenLoaded('designService', fn() => $this->designService?->name),
            'quantity'           => $this->quantity,
            'is_sample'          => $this->is_sample,
            'price_per_unit'     => $this->price_per_unit,
            'line_total'         => $this->line_total,
        ];
    }
}
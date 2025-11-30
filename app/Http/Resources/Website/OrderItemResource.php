<?php

namespace App\Http\Resources\Website;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'quantity'          => $this->quantity,
            'price_per_unit'    => $this->price_per_unit,
            'total_price'       => $this->total_price,
            'formatted_price'   => number_format($this->total_price, 2) . ' ج.م',
            'is_sample'         => $this->is_sample,
            'note'              => $this->note,
            'image_design'      => $this->image_design ? asset('storage/' . $this->image_design) : null,

            // تفاصيل المنتج
            'product' =>new ProductResource($this->product) ,
            // المقاس واللون
            'size' => $this->size ? [
                'id'   => $this->size->id,
                'name' => $this->size->name,
            ] : null,

            'color' => $this->color ? [
                'id'   => $this->color->id,
                'name' => $this->color->name,
                'hex'  => $this->color->hex_code,
            ] : null,

            // طريقة الطباعة
            'printing_method' => $this->printingMethod ? [
                'id'   => $this->printingMethod->id,
                'name' => $this->printingMethod->name,
            ] : null,

            // مواقع الطباعة والتطريز
            'print_locations' => $this->print_locations ?? [],
            'embroider_locations' => $this->embroider_locations ?? [],

            // خيارات إضافية (مثل: طباعة داخلية، ليبل، إلخ)
            'selected_options' => $this->selected_options ?? [],

            // خدمة التصميم إن وجدت
            'design_service' => $this->designService ? [
                'id'   => $this->designService->id,
                'name' => $this->designService->name,
                'price'=> $this->designService->price,
            ] : null,
        ];
    }
}
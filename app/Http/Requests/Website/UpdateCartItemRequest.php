<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->cartItem->cart->is($this->user()?->cart);
    }

  public function rules(): array
{
    return [
        'quantity'             => 'required|integer|min:1',
        'size_id'              => 'nullable|exists:sizes,id',
        'color_id'             => 'nullable|exists:colors,id',
        'printing_method_id'   => 'nullable|exists:printing_methods,id',
        'print_locations'      => 'nullable|array',
        'print_locations.*'    => 'exists:print_locations,id',
        'embroider_locations'  => 'nullable|array',
        'embroider_locations.*'=> 'exists:print_locations,id',
        'selected_options'     => 'nullable|array',
        'design_service_id'    => 'nullable|exists:design_services,id',
        'is_sample'            => 'sometimes|boolean',
    ];
}
}
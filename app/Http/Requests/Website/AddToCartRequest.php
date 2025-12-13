<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'           => 'required|exists:products,id',
            'quantity'             => 'required|integer|min:1',
            'size_id'              => 'nullable|exists:sizes,id',
            'color_id'             => 'nullable|exists:colors,id',
            'printing_method_id'   => 'nullable|exists:printing_methods,id',
            'print_locations'      => 'nullable|array',
            'print_locations.*'    => 'exists:print_locations,id',
            'embroider_locations'  => 'nullable|array',
            'embroider_locations.*' => 'nullable|exists:print_locations,id',
            'selected_options'     => 'nullable|array',
            'selected_options.*.option_name'  => 'nullable|required_with:selected_options|string',
            'selected_options.*.option_value' => 'nullable|required_with:selected_options|string',
            'selected_options.*.option_additional_price' => 'nullable',
            'design_service_id'    => 'nullable|exists:design_services,id',
            'is_sample'            => 'sometimes|boolean',
        ];
    }
}

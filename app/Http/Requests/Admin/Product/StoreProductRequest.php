<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return  [
            // Basic product fields
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|min:0',
            'status_id' => 'required|in:1,2,3',

            // Main image
            'image' => 'required|image|max:2048',

            // Discount
            'has_discount' => 'nullable',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|min:0',

            // Booleans
            'includes_tax' => 'nullable',
            'includes_shipping' => 'nullable',

            // Delivery time
            'from_days' => 'nullable|integer|min:0',
            'to_days' => 'nullable|integer|min:0',

            // Warranty
            'warranty_months' => 'nullable|integer|min:0',

            // Colors
            'colors' => 'nullable|array',
            'colors.*' => 'exists:colors,id',
            'color_prices' => 'nullable|array',
            'color_prices.*' => 'numeric|min:0',

            // Materials
            'materials' => 'nullable|array',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'nullable|numeric|min:0',
            'materials.*.unit' => 'nullable|string',
            'materials.*.additional_price' => 'nullable|numeric|min:0',

            // Printing methods
            'printing_methods' => 'nullable|array',
            'printing_methods.*' => 'exists:printing_methods,id',
            'printing_method_prices' => 'nullable|array',
            'printing_method_prices.*' => 'numeric|min:0',

            // Print locations
            'print_locations' => 'nullable|array',
            'print_locations.*' => 'exists:print_locations,id',
            'print_location_prices' => 'nullable|array',
            'print_location_prices.*' => 'numeric|min:0',

            // Offers
            'offers' => 'nullable|array',
            'offers.*' => 'exists:offers,id',

            // Additional images
            'additional_images' => 'nullable|array',
            'additional_images.*' => 'image|max:2048',
        ];
    }
}

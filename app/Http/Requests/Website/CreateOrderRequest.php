<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_id'         => 'nullable|exists:addresses,id',
            'shipping_address'   => 'nullable|required_without:address_id|string|max:500',
            'customer_name'      => 'nullable|string|max:100',
            'customer_phone'     => 'nullable|string|regex:/^01[0125][0-9]{8}$/',
            'customer_email'     => 'nullable|email',
            'payment_method'     => 'sometimes|in:cash_on_delivery,online',
            'notes'              => 'nullable|string|max:1000',
            'coupon_code'        => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_phone.regex' => 'رقم الهاتف يجب أن يكون مصري صحيح (مثل: 01012345678)',
            'address_id.exists'    => 'العنوان المختار غير موجود',
        ];
    }
}
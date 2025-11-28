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
            'address_id'         => 'nullable|exists:user_addresses,id',
            'shipping_address'   => 'nullable|required_without:address_id|string|max:500',
            'customer_name'      => 'nullable|string|max:100',
            'customer_phone' => 'nullable|string',


            'customer_email'     => 'nullable|email',
            'payment_method'     => 'sometimes|in:cash_on_delivery,credit_card,online',
            'notes'              => 'nullable|string|max:1000',
            'coupon_code'        => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_phone.regex' => 'رقم الجوال يجب أن يكون سعودي صحيح ويبدأ بـ 05 أو +966 (مثال: 0501234567)',

            'address_id.exists'    => 'العنوان المختار غير موجود',
        ];
    }
}

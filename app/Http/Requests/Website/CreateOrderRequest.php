<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * تجهيز البيانات قبل الفاليديشن
     */
    protected function prepareForValidation()
    {
        if ($this->customer_phone) {

            $phone = str_replace(' ', '', $this->customer_phone); // حذف المسافات

            // 05XXXXXXXX → +9665XXXXXXXX
            if (preg_match('/^05\d{8}$/', $phone)) {
                $phone = '+966' . substr($phone, 1);
            }

            // 5XXXXXXXX → +9665XXXXXXXX
            elseif (preg_match('/^5\d{8}$/', $phone)) {
                $phone = '+966' . $phone;
            }

            // 009665XXXXXXXX → +9665XXXXXXXX
            elseif (preg_match('/^009665\d{8}$/', $phone)) {
                $phone = '+966' . substr($phone, 4);
            }

            // 9665XXXXXXXX → +9665XXXXXXXX
            elseif (preg_match('/^9665\d{8}$/', $phone)) {
                $phone = '+' . $phone;
            }

            // دمج القيمة الجديدة
            $this->merge([
                'customer_phone' => $phone
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'address_id'        => 'nullable|exists:user_addresses,id',

            // لو مفيش عنوان لازم يبعت shipping_address
            'shipping_address'  => 'required_without:address_id|nullable|string|max:500',

            'customer_name'     => 'nullable|string|max:100',

            'customer_phone'    => [
                'nullable',
                'string',
                'regex:/^\+9665\d{8}$/'
            ],

            'customer_email'    => 'nullable|email',

            // وسائل الدفع المتاحة
            'payment_method'    => 'required|exists:payment_methods,id',

            'notes'             => 'nullable|string|max:1000',

            'coupon_code'       => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_phone.regex' => 'رقم الجوال يجب أن يكون بالتنسيق الدولي السعودي +9665XXXXXXXX',
            'address_id.exists'    => 'العنوان المختار غير موجود',
            'shipping_address.required_without' => 'يجب إدخال عنوان الشحن إذا لم يتم اختيار عنوان محفوظ',
            'payment_method.in'    => 'طريقة الدفع غير صحيحة',
        ];
    }
}

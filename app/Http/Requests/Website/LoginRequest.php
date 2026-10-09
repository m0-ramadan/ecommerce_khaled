<?php

namespace App\Http\Requests\Website;

use App\Support\SaudiPhone;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'phone'    => ['required', 'string', 'regex:/^05[03456789][0-9]{7}$/'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => SaudiPhone::normalize($this->input('phone')),
        ]);
    }

    public function messages(): array
    {
        return [
            'phone.required' => 'رقم الجوال مطلوب.',
            'phone.regex'    => 'يرجى إدخال رقم جوال سعودي صالح.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min'   => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',
        ];
    }
}

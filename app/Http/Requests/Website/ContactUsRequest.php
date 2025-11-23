<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;


class ContactUsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'phone'      => 'required|string|max:20',
            'email'      => 'required|email|max:150',
            'company'    => 'nullable|string|max:150',
            'message'    => 'required|string|max:2000',
        ];
    }
}
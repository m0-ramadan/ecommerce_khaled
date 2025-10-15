<?php

namespace App\Http\Requests\Admin\UserRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'product_id' => 'required|exists:products,id',
            // 'details' => 'required|string',
            // 'tax' => 'required|numeric',
            // 'zakat' => 'required|numeric',
            // 'visa_percentage' => 'required|numeric',
            // 'discount' => 'required|numeric',
            // 'shipping' => 'required|numeric',
            // 'developement' => 'required|numeric',
            // 'advertising' => 'required|numeric',
            // 'Insurance' => 'required|numeric',
        ];
    }
}

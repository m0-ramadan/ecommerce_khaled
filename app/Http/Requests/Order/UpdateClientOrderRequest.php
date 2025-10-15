<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UpdateClientOrderRequest extends FormRequest
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
            'user_name' => 'required',
            'user_email' => 'required',
            'user_phone' => 'required',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:cities,id',
            'country_ref_code' => 'required',
            'neighborhood' => 'required',
            'zip_code' => 'required',
            'address' => 'required',
            'payment_ref_code' => 'required',
            'response_status' => 'required',
            'rate_status' => 'nullable',
        ];
    }

    public function attributes()
    {
        return [
            'state_id' => 'City',
            'country_id' => 'Country',
            'zip_code' => 'Zip code',
            'address' => 'Address',
            'user_name' => 'Name',
            'user_email' => 'Email',
            'user_phone' => 'Phone',
        ];
    }

    public function prepareForValidation()
    {
        if($this->route()->order->payment_ref_code)
        {
            throw  ValidationException::withMessages([
                'payment_ref_code' => ['You cant change this payment any more.'],
            ]);
        }
    }
}

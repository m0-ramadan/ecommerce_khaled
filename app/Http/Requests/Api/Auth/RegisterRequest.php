<?php

namespace App\Http\Requests\Api\Auth;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'nullable|unique:clients,email',
            'phone' => 'required|unique:clients,phone',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'address' => 'nullable',
            'state_id'=>'nullable'
         ];
    }

    protected function passedValidation(): void
    {
        $this->merge(['phone' =>"+968". $this->phone]);
    }
}

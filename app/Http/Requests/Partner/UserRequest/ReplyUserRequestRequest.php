<?php

namespace App\Http\Requests\Partner\UserRequest;

use Illuminate\Foundation\Http\FormRequest;

class ReplyUserRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->route()->userRequest->client_id === auth('api')->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'details' => 'nullable|string',
            'status' => 'required|string|in:accept,reject',
        ];
    }
}

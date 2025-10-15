<?php

namespace App\Http\Requests\Admin\Message;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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
            'clients' => 'nullable|array',
            'clients.*' => 'nullable|exists:clients,id',
            'email' => 'nullable|boolean',
            'file' => 'nullable|file',
            'title' => 'required|string',
            'message' => 'required|string',
            'client_email' => 'nullable|email',
            'client_id' => 'nullable',
        ];
    }
}

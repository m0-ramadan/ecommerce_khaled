<?php

namespace App\Http\Requests\Admin\Obligation;

use App\Models\Obligations;
use Illuminate\Foundation\Http\FormRequest;

class StoreObligationRequest extends FormRequest
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
            'subject' => 'required|string',
            // 'monthly_dues' => 'required|numeric',
            // 'annual_dues' => 'required|numeric',
            'date' => 'required|date',
            'type' => 'required|numeric|in:' . implode(',', Obligations::OBLIGATION_TYPE),
            'payment_method' => 'required|string',
            'total_money' => 'required|numeric',
            'file' => 'required|file',
            'total_money' => 'required|numeric',
            'client_id' => 'required|exists:clients,id',
        ];
    }
}

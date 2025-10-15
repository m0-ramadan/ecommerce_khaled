<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewSettingRequest extends FormRequest
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
            'image' => 'nullable|image',
            'title' => 'nullable',
            'title_ar' => 'required|string',
            'title_en' => 'required|string',
            'title_it' => 'required|string',
        ];
    }

    public function prepareForValidation(){
        $this->merge([
            'title' => [
                'ar' => $this->title_ar,
                'en' => $this->title_en,
                'it' => $this->title_it,
            ]
        ]);
    }
}

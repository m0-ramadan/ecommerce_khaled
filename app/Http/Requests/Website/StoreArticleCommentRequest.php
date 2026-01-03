<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'article_id' => 'required|exists:articles,id',
            'content' => 'required|string|min:3|max:1000',
            'parent_id' => 'nullable|exists:article_comments,id'
        ];

        if (!auth()->check()) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        return $rules;
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:50000|min:1',
            'language' => 'required|string|in:python,javascript,java,cpp,php,go,rust,sql,typescript',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Code is required',
            'code.max' => 'Code must not exceed 50KB',
            'language.required' => 'Programming language is required',
            'language.in' => 'Selected language is not supported',
            'title.max' => 'Title must not exceed 255 characters',
            'description.max' => 'Description must not exceed 1000 characters',
        ];
    }
}

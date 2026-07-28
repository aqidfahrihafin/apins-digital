<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'content' => ['required', 'string', 'max:3000'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
            'website' => ['nullable', 'string', 'max:255'],
        ];
    }
}

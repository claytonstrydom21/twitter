<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:280'],
            'image' => ['nullable', 'image', 'max:5120']
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Post content is required',
            'content.max' => 'Post content cannot exceed 280 characters',
            'image.image' => 'The file must be an image',
            'image.max' => 'The image must not be larger than 5MB'
        ];
    }
}

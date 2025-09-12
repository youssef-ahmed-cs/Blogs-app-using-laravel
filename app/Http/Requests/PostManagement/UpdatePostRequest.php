<?php

namespace App\Http\Requests\PostManagement;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => 'required|string|max:255|min:3',
            'description' => 'string|max:1000|min:3',
            'post_creator' => 'required|string|exists:users,id',
            'image_post' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ];
    }
}

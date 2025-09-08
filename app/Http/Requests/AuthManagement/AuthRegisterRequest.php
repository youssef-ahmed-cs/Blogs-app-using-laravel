<?php

namespace App\Http\Requests\AuthManagement;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50|min:3',
            //'name' => ['required', 'string', 'max:255'],
            'email' => 'required|string|email|max:50|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

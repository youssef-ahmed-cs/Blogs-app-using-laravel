<?php

namespace App\Http\Requests\AuthManagement;

use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

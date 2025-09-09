<?php

namespace App\Http\Requests\CommentManagement;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string|min:3|max:1000',
//            'post_id' => 'required|exists:posts,id',
//            'user_id' => 'required|exists:users,id',
            'parent_id' => 'nullable|exists:comments,id',
        ];
    }
}

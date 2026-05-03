<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:1', 'max:50'],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore(auth('api')->id()),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'User name must be a string.',
            'name.min' => 'User name must be at least 1 character.',
            'name.max' => 'User name may not be greater than 50 characters.',

            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
        ];
    }
}

<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PatchRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => ['sometimes', 'filled', 'string', 'max:50'],

            'email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($userId)],

            'phone' => ['nullable', 'string', 'regex:/^\+?[0-9]+$/', 'max:20'],

            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.filled' => 'User name must not be empty.',
            'name.string' => 'User name must be a string.',
            'name.max' => 'User name may not be greater than 50 characters.',

            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',

            'phone.string' => 'Phone number must be a valid string.',
            'phone.regex' => 'Phone number may only contain numbers and optional leading +.',
            'phone.max' => 'Phone number may not be greater than :max characters.',

            'is_active.boolean' => 'Active status must be true or false.',
        ];
    }
}

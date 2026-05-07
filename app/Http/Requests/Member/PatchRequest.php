<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatchRequest extends FormRequest
{
    public function rules(): array
    {
        $memberId = $this->route('member')->id;

        return [
            'name' => ['sometimes', 'string', 'max:50'],

            'email' => [
                'sometimes',
                'email',
                'max:100',
                Rule::unique('members', 'email')->ignore($memberId),
            ],

            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\+?[0-9]+$/'],

            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Member name must be a valid string.',
            'name.max' => 'Member name may not be greater than :max characters.',

            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'Email may not be greater than :max characters.',
            'email.unique' => 'This email address is already registered.',

            'phone.string' => 'Phone number must be a valid string.',
            'phone.regex' => 'Phone number may only contain numbers and optional leading +.',
            'phone.max' => 'Phone number may not be greater than :max characters.',

            'is_active.boolean' => 'Active status must be true or false.',
        ];
    }
}

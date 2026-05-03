<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],

            'email' => ['required', 'email', 'unique:members,email'],

            'phone' => ['nullable', 'string', 'regex:/^\+?[0-9]+$/', 'max:20'],

            'is_active' => ['sometimes', 'boolean'],

            'hobbies' => ['nullable', 'array'],
            'hobbies.*' => ['integer', 'exists:hobbies,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Member name is required.',
            'name.string' => 'Member name must be a valid string.',
            'name.max' => 'Member name may not be greater than :max characters.',

            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',

            'phone.string' => 'Phone number must be a valid string.',
            'phone.regex' => 'Phone number may only contain numbers and optional leading +.',
            'phone.max' => 'Phone number may not be greater than :max characters.',

            'is_active.boolean' => 'Active status must be true or false.',

            'hobbies.array' => 'Hobbies must be provided as an array.',
            'hobbies.*.integer' => 'Each hobby must be a valid ID.',
            'hobbies.*.exists' => 'One or more selected hobbies do not exist.',
        ];
    }
}

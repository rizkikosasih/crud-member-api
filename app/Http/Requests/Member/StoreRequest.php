<?php

namespace App\Http\Requests\Member;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:members,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'hobbies' => ['nullable', 'array'],
            'hobbies.*' => ['exists:hobbies,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Member name is required.',
            'name.string' => 'Member name must be a valid string.',
            'name.max' => 'Member name may not be greater than :max characters.',

            'phone.required' => 'Phone number is required.',
            'phone.string' => 'Phone number must be a valid string.',
            'phone.max' => 'Phone number may not be greater than :max characters.',

            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',

            'is_active.required' => 'Active status is required.',
            'is_active.boolean' => 'Active status must be true or false.',

            'hobbies.required' => 'At least one hobby must be selected.',
            'hobbies.array' => 'Hobbies must be provided as an array.',
            'hobbies.*.exists' => 'One or more selected hobbies are invalid.',
        ];
    }
}

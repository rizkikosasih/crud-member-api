<?php

namespace App\Http\Requests\Member;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $memberId = $this->route('member')->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('members', 'email')->ignore($memberId),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'hobbies' => ['nullable', 'array'],
            'hobbies.*' => ['integer', 'exists:hobbies,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Member name must be a valid string.',
            'name.max' => 'Member name may not be greater than :max characters.',

            'phone.string' => 'Phone number must be a valid string.',
            'phone.max' => 'Phone number may not be greater than :max characters.',

            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',

            'is_active.boolean' => 'Active status must be true or false.',

            'hobbies.array' => 'Hobbies must be provided as an array.',
            'hobbies.*.exists' => 'One or more selected hobbies are invalid.',
        ];
    }
}

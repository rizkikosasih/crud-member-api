<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => ['required', 'string', 'max:50'],

            'email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($userId)],

            'is_active' => ['sometimes', 'boolean'],

            'roles' => ['sometimes', 'array', 'min:1'],
            'roles.*' => ['string', Rule::exists('roles', 'name')->where('guard_name', 'api')],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'User name is required.',
            'name.string' => 'User name must be a string.',
            'name.min' => 'User name must be at least 1 character.',
            'name.max' => 'User name may not be greater than 50 characters.',

            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',

            'roles.array' => 'Roles must be provided as an array.',
            'roles.min' => 'At least one role must be assigned.',
            'roles.*.string' => 'Each role must be a valid role name.',
            'roles.*.exists' => 'One or more selected roles are invalid.',

            'is_active.boolean' => 'Active status must be true or false.',
        ];
    }
}

<?php

namespace App\Http\Requests\Member;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MemberHobbyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'hobby_ids' => ['required', 'array', 'min:1'],
            'hobby_ids.*' => ['integer', 'distinct', 'exists:hobbies,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'hobby_ids.required' => 'Hobby IDs is required.',
            'hobby_ids.array' => 'Hobby IDs must be an array.',
            'hobby_ids.min' => 'At least one hobby must be selected.',
            'hobby_ids.*.integer' => 'Each hobby ID must be an integer.',
            'hobby_ids.*.distinct' => 'Duplicate hobby IDs are not allowed.',
            'hobby_ids.*.exists' => 'One or more selected hobbies do not exist.',
        ];
    }
}

<?php

namespace Creasi\Base\Http\Requests\Company;

use Creasi\Base\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateRequest extends FormRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        $user = $this->user();

        return [
            'name' => ['required', 'string', Rule::unique('users', 'name')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'old-password' => ['required_unless:password,null'],
        ];
    }
}

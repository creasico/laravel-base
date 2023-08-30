<?php

namespace Creasi\Base\Http\Requests\Company;

use Creasi\Base\Http\Requests\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'alias' => ['nullable', 'string'],
            'email' => ['required', 'email'],
            'phone_number' => ['nullable', 'numeric'],
            'summary' => ['nullable', 'string', 'max:200'],
        ];
    }
}

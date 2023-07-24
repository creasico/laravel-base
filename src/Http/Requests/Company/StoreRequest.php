<?php

namespace Creasi\Base\Http\Requests\Company;

use Creasi\Base\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'alias' => ['nullable', 'string', Rule::unique('businesses', 'alias')],
            'email' => ['required', 'email', Rule::unique('businesses', 'email')],
            'phone_number' => ['nullable', 'numeric'],
            'summary' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace Creasi\Base\Http\Requests\Stakeholder;

use Creasi\Base\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone_number' => ['nullable', 'numeric'],
            'summary' => ['nullable', 'string'],
        ];
    }
}
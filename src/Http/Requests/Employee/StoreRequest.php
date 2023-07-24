<?php

namespace Creasi\Base\Http\Requests\Employee;

use Creasi\Base\Http\Requests\FormRequest;
use Creasi\Base\Models\Enums\Gender;
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
            'alias' => ['nullable', 'string', Rule::unique('personnels', 'alias')],
            'email' => ['required', 'email', Rule::unique('personnels', 'email')],
            'phone' => ['nullable', 'numeric'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'summary' => ['nullable', 'string'],
        ];
    }
}

<?php

namespace Creasi\Base\Http\Requests\Stakeholder;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Creasi\Base\Models\Contracts\Stakeholder;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest implements FormRequestContract
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

    public function fulfill(Stakeholder $stakeholder)
    {
        return $stakeholder->update($this->validated());
    }
}

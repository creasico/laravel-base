<?php

namespace Creasi\Base\Http\Requests\Employee;

use Creasi\Base\Contracts\Employee;
use Creasi\Base\Contracts\FormRequest as FormRequestContract;
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
            'phone' => ['nullable', 'numeric'],
            'summary' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function fulfill(Employee $employee)
    {
        return $employee->update($this->validated());
    }
}

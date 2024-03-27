<?php

namespace Creasi\Base\Http\Requests\Employee;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Creasi\Base\Database\Models\Contracts\Employee;
use Creasi\Base\Database\Models\Personnel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest implements FormRequestContract
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        $key = $this->route('employee');

        return [
            'name' => ['required', 'string', 'max:150'],
            'alias' => ['nullable', 'string', 'max:50', Rule::unique(Personnel::class, 'alias')->ignore($key)],
            'email' => ['required', 'email', 'max:150', Rule::unique(Personnel::class, 'email')->ignore($key)],
            'phone' => ['nullable', 'numeric', 'max_digits:20'],
            'summary' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function fulfill(Employee $employee)
    {
        $updated = $employee->update($this->validated());

        return $updated;
    }
}

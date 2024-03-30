<?php

namespace Creasi\Base\Http\Requests\Personnel;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Creasi\Base\Database\Models\Contracts\Personnel;
use Creasi\Base\Database\Models\Person;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest implements FormRequestContract
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        $key = $this->route('personnel');

        return [
            'name' => ['required', 'string', 'max:150'],
            'alias' => ['nullable', 'string', 'max:50', Rule::unique(Person::class, 'alias')->ignore($key)],
            'email' => ['required', 'email', 'max:150', Rule::unique(Person::class, 'email')->ignore($key)],
            'phone' => ['nullable', 'numeric', 'max_digits:20'],
            'summary' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function fulfill(Personnel $employee)
    {
        $updated = $employee->update($this->validated());

        return $updated;
    }
}

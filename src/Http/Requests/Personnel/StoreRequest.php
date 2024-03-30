<?php

namespace Creasi\Base\Http\Requests\Personnel;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\Person;
use Creasi\Base\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest implements FormRequestContract
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'alias' => ['nullable', 'string', 'max:50', Rule::unique(Person::class, 'alias')],
            'email' => ['required', 'email', 'max:150', Rule::unique(Person::class, 'email')],
            'phone' => ['nullable', 'numeric', 'max_digits:20'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'summary' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function fulfill(Company $company)
    {
        $employee = $company->employees()->create($this->validated());

        return $employee;
    }
}

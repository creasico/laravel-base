<?php

namespace Creasi\Base\Http\Requests\Employee;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Creasi\Base\Database\Models\Contracts\Company;
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
            'name' => ['required', 'string'],
            'alias' => ['nullable', 'string', Rule::unique('personnels', 'alias')],
            'email' => ['required', 'email', Rule::unique('personnels', 'email')],
            'phone' => ['nullable', 'numeric'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'summary' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function fulfill(Company $company)
    {
        return $company->employees()->create($this->validated());
    }
}

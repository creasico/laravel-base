<?php

namespace Creasi\Base\Http\Requests\Company;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\Personnel;
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
            'alias' => ['nullable', 'string', 'max:50', Rule::unique(Personnel::class, 'alias')],
            'email' => ['required', 'email', 'max:150', Rule::unique(Personnel::class, 'email')],
            'phone' => ['nullable', 'numeric', 'max_digits:20'],
            'summary' => ['nullable', 'string', 'max:200'],
        ];
    }

    public function fulfill(Company $company)
    {
        return $company->create($this->validated());
    }
}

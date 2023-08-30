<?php

namespace Creasi\Base\Http\Requests\Address;

use Creasi\Base\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        $types = config('creasi.base.address.types', AddressType::class);

        return [
            'type' => ['required', 'numeric', Rule::enum($types)],
            'line' => ['required', 'string'],
            'rt' => ['required', 'numeric'],
            'rw' => ['required', 'numeric'],
            'village_code' => ['required', 'numeric'],
            'district_code' => ['required', 'numeric'],
            'regency_code' => ['required', 'numeric'],
            'province_code' => ['required', 'numeric'],
            'postal_code' => ['required', 'numeric'],
            'summary' => ['nullable', 'string', 'max:200'],
        ];
    }
}

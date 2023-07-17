<?php

namespace Creasi\Base\Http\Requests\Address;

use Creasi\Base\Http\Requests\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'is_resident' => ['required', 'boolean'],
            'line' => ['required', 'string'],
            'rt' => ['required', 'numeric'],
            'rw' => ['required', 'numeric'],
            'village_code' => ['required', 'numeric'],
            'district_code' => ['required', 'numeric'],
            'regency_code' => ['required', 'numeric'],
            'province_code' => ['required', 'numeric'],
            'postal_code' => ['required', 'numeric'],
            'summary' => ['string', 'nullable'],
        ];
    }
}

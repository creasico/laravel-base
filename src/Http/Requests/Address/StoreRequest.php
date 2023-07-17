<?php

namespace Creasi\Base\Http\Requests\Address;

use Creasi\Base\Http\Requests\FormRequest;
use Creasi\Base\Models\Address;
use Creasi\Nusa\Contracts\HasAddresses;

class StoreRequest extends FormRequest
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

    public function storeFor(HasAddresses $entity)
    {
        $address = Address::query()->make($this->validated());

        return $entity->addresses()->save($address);
    }
}

<?php

namespace Creasi\Base\Http\Requests\Address;

use Creasi\Base\Contracts\FormRequest as FormRequestContract;
use Creasi\Base\Models\Address;
use Creasi\Base\Models\Enums\AddressType;
use Creasi\Nusa\Contracts\HasAddresses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest implements FormRequestContract
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

    public function storeFor(HasAddresses $entity)
    {
        $address = Address::query()->make($this->validated());

        return $entity->addresses()->save($address);
    }
}

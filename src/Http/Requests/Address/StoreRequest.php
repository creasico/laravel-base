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
            // .
        ];
    }

    public function storeFor(HasAddresses $entity)
    {
        $address = Address::query()->make($this->validated());

        return $entity->addresses()->save($address);
    }
}

<?php

namespace Creasi\Base\Http\Requests\Address;

use Creasi\Base\Http\Requests\AbstractIndexRequest;
use Creasi\Nusa\Contracts\HasAddresses;

class IndexRequest extends AbstractIndexRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return \array_merge(parent::rules(), []);
    }

    public function fulfill(HasAddresses $entity)
    {
        $items = $entity->addresses()->latest();

        return $items;
    }
}

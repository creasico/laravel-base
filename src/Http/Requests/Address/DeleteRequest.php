<?php

namespace Creasi\Base\Http\Requests\Address;

use Creasi\Base\Database\Models\Address;
use Creasi\Base\Http\Requests\AbstractDeleteRequest;

class DeleteRequest extends AbstractDeleteRequest
{
    public function fulfill(Address $address)
    {
        $deleted = $this->boolean('force')
            ? $address->forceDelete()
            : $address->delete();

        return $deleted;
    }
}

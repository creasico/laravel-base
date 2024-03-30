<?php

namespace Creasi\Base\Http\Requests\Personnel;

use Creasi\Base\Database\Models\Contracts\Personnel;
use Creasi\Base\Http\Requests\AbstractDeleteRequest;

class DeleteRequest extends AbstractDeleteRequest
{
    public function fulfill(Personnel $employee)
    {
        $deleted = $this->boolean('force')
            ? $employee->forceDelete()
            : $employee->delete();

        return $deleted;
    }
}

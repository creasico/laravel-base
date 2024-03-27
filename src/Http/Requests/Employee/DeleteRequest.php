<?php

namespace Creasi\Base\Http\Requests\Employee;

use Creasi\Base\Database\Models\Contracts\Employee;
use Creasi\Base\Http\Requests\AbstractDeleteRequest;

class DeleteRequest extends AbstractDeleteRequest
{
    public function fulfill(Employee $employee)
    {
        $deleted = $this->boolean('force')
            ? $employee->forceDelete()
            : $employee->delete();

        return $deleted;
    }
}

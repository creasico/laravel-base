<?php

namespace Creasi\Base\Http\Requests\Company;

use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Http\Requests\AbstractDeleteRequest;

class DeleteRequest extends AbstractDeleteRequest
{
    public function fulfill(Company $company)
    {
        $deleted = $this->boolean('force')
            ? $company->forceDelete()
            : $company->delete();

        return $deleted;
    }
}

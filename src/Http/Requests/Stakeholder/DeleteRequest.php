<?php

namespace Creasi\Base\Http\Requests\Stakeholder;

use Creasi\Base\Database\Models\Contracts\Stakeholder;
use Creasi\Base\Http\Requests\AbstractDeleteRequest;

class DeleteRequest extends AbstractDeleteRequest
{
    public function fulfill(Stakeholder $stakeholder)
    {
        $deleted = $this->boolean('force')
            ? $stakeholder->forceDelete()
            : $stakeholder->delete();

        return $deleted;
    }
}

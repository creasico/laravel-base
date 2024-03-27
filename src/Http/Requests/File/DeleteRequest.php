<?php

namespace Creasi\Base\Http\Requests\File;

use Creasi\Base\Database\Models\File;
use Creasi\Base\Http\Requests\AbstractDeleteRequest;

class DeleteRequest extends AbstractDeleteRequest
{
    public function fulfill(File $file)
    {
        $deleted = $this->boolean('force')
            ? $file->forceDelete()
            : $file->delete();

        return $deleted;
    }
}

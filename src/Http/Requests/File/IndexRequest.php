<?php

namespace Creasi\Base\Http\Requests\File;

use Creasi\Base\Database\Models\Contracts\HasFiles;
use Creasi\Base\Http\Requests\AbstractIndexRequest;

class IndexRequest extends AbstractIndexRequest
{
    /**
     * @return array<string, array>
     */
    public function rules(): array
    {
        return \array_merge(parent::rules(), []);
    }

    public function fulfill(HasFiles $entity)
    {
        $items = $entity->files()->latest();

        return $items;
    }
}

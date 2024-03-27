<?php

namespace Creasi\Base\Http\Resources\File;

use Creasi\Base\Http\Resources\Collection;
use Illuminate\Http\Request;

class FileCollection extends Collection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [],
            'links' => [],
        ];
    }
}

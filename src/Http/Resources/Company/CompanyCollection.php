<?php

namespace Creasi\Base\Http\Resources\Company;

use Creasi\Base\Http\Resources\AsEntity;
use Creasi\Base\Http\Resources\Collection;
use Illuminate\Http\Request;

class CompanyCollection extends Collection
{
    use AsEntity;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => $this->meta(),
            'links' => $this->links(),
        ];
    }
}

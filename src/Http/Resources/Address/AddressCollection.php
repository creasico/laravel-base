<?php

namespace Creasi\Base\Http\Resources\Address;

use Creasi\Base\Http\Resources\AsAddress;
use Creasi\Base\Http\Resources\Collection;
use Illuminate\Http\Request;

/**
 * @property-read \Illuminate\Support\Collection<int, \Creasi\Base\Models\Address> $collection
 */
class AddressCollection extends Collection
{
    use AsAddress;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => $this->addressMeta(),
        ];
    }
}

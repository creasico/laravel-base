<?php

namespace Creasi\Base\Http\Resources\Address;

use Creasi\Base\Http\Resources\AsAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read \Creasi\Base\Models\Address $resource
 */
class AddressResource extends JsonResource
{
    use AsAddress;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->additional([
            'meta' => $this->addressMeta(),
        ]);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->forAddress($this->resource);
    }
}

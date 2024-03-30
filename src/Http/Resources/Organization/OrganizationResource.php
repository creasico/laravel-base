<?php

namespace Creasi\Base\Http\Resources\Organization;

use Creasi\Base\Http\Resources\AsEntity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read \Creasi\Base\Database\Models\OrganizationRelative $resource
 */
class OrganizationResource extends JsonResource
{
    use AsEntity;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->additional([
            'meta' => $this->meta(),
            'links' => $this->links(),
        ]);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->forStakeholder($this->resource);
    }
}

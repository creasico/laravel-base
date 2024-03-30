<?php

namespace Creasi\Base\Http\Resources;

use Creasi\Base\Enums\Education;
use Creasi\Base\Enums\TaxStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \Creasi\Base\Database\Models\User
 */
class ProfileResource extends JsonResource
{
    use AsEntity;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->additional([
            'meta' => [
                // 'educations' => Education::toOptions(),
                // 'tax_statuses' => TaxStatus::toOptions(),
            ],
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
        $resource = $this->forPerson($this->resource->profile);

        $resource[$this->resource->getKeyName()] = $this->resource->getKey();

        return $resource;
    }
}

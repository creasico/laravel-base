<?php

namespace Creasi\Base\Http\Resources;

use Creasi\Base\Models\Enums\Education;
use Creasi\Base\Models\Enums\TaxStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \Creasi\Base\Models\User
 */
class ProfileResource extends JsonResource
{
    use AsEntity;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->additional([
            'meta' => [
                'educations' => Education::toOptions(),
                'tax_statuses' => TaxStatus::toOptions(),
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
        $resource = $this->forPersonnel($this->identity);

        $resource[$this->getKeyName()] = $this->getKey();

        return $resource;
    }
}

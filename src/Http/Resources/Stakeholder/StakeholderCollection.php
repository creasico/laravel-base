<?php

namespace Creasi\Base\Http\Resources\Stakeholder;

use Creasi\Base\Http\Resources\Collection;
use Illuminate\Http\Request;

class StakeholderCollection extends Collection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}

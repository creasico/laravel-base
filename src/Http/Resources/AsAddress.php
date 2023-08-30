<?php

namespace Creasi\Base\Http\Resources;

use Creasi\Base\Models\Address;
use Creasi\Base\Models\Enums\AddressType;

trait AsAddress
{
    final protected function addressMeta(): array
    {
        return [
            'types' => AddressType::toOptions(),
        ];
    }

    final protected function forAddress(Address $address): array
    {
        if (! $address->exists) {
            return [];
        }

        return [
            $address->getKeyName() => $address->getKey(),
            'type' => $address->type ? [
                'value' => $address->type?->value,
                'label' => $address->type?->label(),
            ] : null,
            'line' => $address->line,
            'rt' => $address->rt,
            'rw' => $address->rw,
            'village' => $address->village?->only('code', 'name'),
            'district' => $address->district?->only('code', 'name'),
            'regency' => $address->regency?->only('code', 'name'),
            'province' => $address->province?->only('code', 'name'),
            'postal_code' => $address->postal_code,
            'summary' => $address->summary,
            'created_at' => $address->created_at,
            'updated_at' => $address->updated_at,
        ];
    }
}

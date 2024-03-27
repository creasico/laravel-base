<?php

namespace Creasi\Base\Http\Resources;

use Creasi\Base\Database\Models\BusinessRelative;
use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\Contracts\Employee;
use Creasi\Base\Database\Models\Contracts\Stakeholder;

trait AsEntity
{
    final protected function links(): array
    {
        return [];
    }

    final protected function meta(): array
    {
        return [];
    }

    /**
     * @param  \Creasi\Base\Database\Models\Personnel|null  $entity
     */
    final protected function forPersonnel(Employee $entity): array
    {
        return [
            $entity->getKeyName() => $entity->getKey(),
            'avatar' => $entity->avatar?->only('url', 'title'),
            'name' => $entity->name,
            'alias' => $entity->alias,
            'gender' => $entity->gender?->toArray(),
            'nik' => $entity->nik,
            'email' => $entity->email,
            'phone' => $entity->phone,
            'prefix' => $entity->prefix,
            'suffix' => $entity->suffix,
            'birth_date' => $entity->birth_date,
            'birth_place' => $entity->birthPlace?->only('code', 'name'),
            'summary' => $entity->summary,
        ];
    }

    /**
     * @param  \Creasi\Base\Database\Models\Business|null  $entity
     */
    final protected function forCompany(Company $entity): array
    {
        return [
            $entity->getKeyName() => $entity->getKey(),
            'avatar' => $entity->avatar?->only('url', 'title'),
            'name' => $entity->name,
            'alias' => $entity->alias,
            'email' => $entity->email,
            'phone' => $entity->phone,
            'summary' => $entity->summary,
        ];
    }

    final protected function forStakeholder(BusinessRelative|Stakeholder $entity): array
    {
        if ($entity instanceof BusinessRelative) {
            return \array_merge([
                'type' => $entity->type?->toArray(),
            ], $this->forStakeholder($entity->stakeholder));
        }

        $arr = $entity instanceof Company
            ? $this->forCompany($entity)
            : $this->forPersonnel($entity);

        return $arr;
    }
}

<?php

namespace Creasi\Base\Http\Resources;

use Creasi\Base\Contracts\Company;
use Creasi\Base\Contracts\Employee;
use Creasi\Base\Contracts\Stakeholder;
use Creasi\Base\Models\BusinessRelative;
use Creasi\Base\Models\Profile;

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
     * @param  \Creasi\Base\Models\Personnel|null  $entity
     */
    final protected function forPersonnel(Employee $entity = null, bool $showProfile = true): array
    {
        $arr = [
            $entity->getKeyName() => $entity->getKey(),
            'avatar' => $entity?->avatar?->only('url', 'title'),
            'fullname' => $entity?->name,
            'nickname' => $entity?->alias,
            'gender' => $entity?->gender?->toArray(),
            'email' => $entity?->email,
            'phone' => $entity?->phone,
            'summary' => $entity?->summary,
        ];

        if ($showProfile && $profile = $entity?->profile) {
            $arr = \array_merge($arr, $this->forProfile($profile));
        }

        return $arr;
    }

    /**
     * @param  \Creasi\Base\Models\Business|null  $entity
     */
    final protected function forCompany(Company $entity = null): array
    {
        $arr = [
            $entity->getKeyName() => $entity->getKey(),
            'avatar' => $entity?->avatar?->only('url', 'title'),
            'legalname' => $entity?->name,
            'aliasname' => $entity?->alias,
            'email' => $entity?->email,
            'phone' => $entity?->phone,
            'summary' => $entity?->summary,
        ];

        return $arr;
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
            : $this->forPersonnel($entity, false);

        // $arr['type'] = \class_basename($entity);

        return $arr;
    }

    final protected function forProfile(Profile $profile): array
    {
        if (empty($profile)) {
            return [];
        }

        return [
            'nik' => $profile->nik,
            'prefix' => $profile->prefix,
            'suffix' => $profile->suffix,
            'birth_date' => $profile->birth_date,
            'birth_place' => $profile->birthPlace?->only('code', 'name'),
            'education' => $profile->education?->value,
            'religion' => $profile->religion?->toArray(),
            'tax_status' => $profile->tax_status?->toArray(),
            'tax_id' => $profile->tax_id,
        ];
    }
}

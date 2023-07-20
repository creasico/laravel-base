<?php

namespace Creasi\Base\Models;

use Creasi\Base\Contracts\Employer;
use Creasi\Base\Contracts\HasTaxInfo;
use Creasi\Base\Models\Concerns\AsEmployer;
use Creasi\Base\Models\Concerns\WithTaxInfo;
use Creasi\Base\Models\Enums\BusinessRelativeType;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $individualRelatives
 * @property-read \Illuminate\Database\Eloquent\Collection<int, static> $companyRelatives
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $stakeholders
 * @property-read BusinessRelative $stakeholder
 *
 * @method static \Database\Factories\BusinessFactory<static> factory()
 */
class Business extends Entity implements HasTaxInfo, Employer
{
    use AsEmployer;
    use WithTaxInfo;

    protected $fillable = [
        // .
    ];

    protected $casts = [
        // .
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|BusinessRelative
     */
    protected function relatives(string $relative, bool $forward = true)
    {
        $relation = $forward
            ? $this->morphedByMany($relative, 'stakeholder', 'business_relatives', 'business_id')
            : $this->morphToMany(static::class, 'stakeholder', 'business_relatives', null, 'business_id');

        return $relation->using(BusinessRelative::class)->withPivot('type', 'is_internal', 'code');
    }

    public function companyRelatives()
    {
        return $this->relatives(static::class)->as('stakeholder');
    }

    public function individualRelatives()
    {
        return $this->relatives(Personnel::class)->as('stakeholder');
    }

    public function stakeholders()
    {
        return $this->hasMany(BusinessRelative::class);
    }

    public function addStakeholder(
        BusinessRelativeType $type,
        Entity $stakeholder,
        bool $internal = null,
    ): static {
        $this->relatives(\get_class($stakeholder))->attach($stakeholder, [
            'type' => $type,
            'is_internal' => $internal ?? $type->isInternal(),
        ]);

        return $this->fresh();
    }
}

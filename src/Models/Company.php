<?php

namespace Creasi\Base\Models;

use Creasi\Base\Models\Enums\CompanyRelativeType;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $employees
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $individualRelatives
 * @property-read \Illuminate\Database\Eloquent\Collection<int, static> $companyRelatives
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CompanyRelative> $stakeholders
 * @property-read Employment $employment
 * @property-read CompanyRelative $stakeholder
 *
 * @method static \Database\Factories\CompanyFactory<static> factory()
 */
class Company extends Entity
{
    protected $casts = [
        // .
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Personnel
     */
    public function employees()
    {
        return $this->belongsToMany(Personnel::class, 'employments', 'company_id', 'employee_id')
            ->withPivot('is_primary', 'type', 'status', 'start_date', 'finish_date', 'remark')
            ->using(Employment::class)
            ->as('employment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|CompanyRelative
     */
    protected function businessRelative(string $relative, bool $forward = true)
    {
        $relation = $forward
            ? $this->morphedByMany($relative, 'stakeholder', 'company_relatives', 'company_id')
            : $this->morphToMany(static::class, 'stakeholder', 'company_relatives', null, 'company_id');

        return $relation->using(CompanyRelative::class)->withPivot('type', 'remark', 'is_internal');
    }

    public function companyRelatives()
    {
        return $this->businessRelative(static::class)->as('stakeholder');
    }

    public function individualRelatives()
    {
        return $this->businessRelative(Personnel::class)->as('stakeholder');
    }

    public function stakeholders()
    {
        return $this->hasMany(CompanyRelative::class);
    }

    public function addStakeholder(
        CompanyRelativeType $type,
        Entity $stakeholder,
        bool $internal = null,
        string $remark = null,
    ): static {
        $this->businessRelative(\get_class($stakeholder))->attach($stakeholder, [
            'type' => $type,
            'remark' => $remark,
            'is_internal' => $internal ?? $type->isInternal(),
        ]);

        return $this->fresh();
    }
}

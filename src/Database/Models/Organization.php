<?php

namespace Creasi\Base\Database\Models;

use Carbon\CarbonInterface;
use Creasi\Base\Database\Models\Concerns\AsCompany;
use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Enums\PersonnelStatus;
use Creasi\Base\Enums\StakeholderType;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read OrganizationRelative $stakeholder
 *
 * @method static \Creasi\Base\Database\Factories\OrganizationFactory<Organization> factory()
 */
class Organization extends Entity implements Company
{
    use AsCompany;

    protected $fillable = [];

    protected $casts = [];

    /**
     * {@inheritdoc}
     */
    protected function relatives(string $relative, bool $forward = true): MorphToMany
    {
        $relation = $forward
            ? $this->morphedByMany($relative, 'stakeholder', 'organizations_relatives', 'organization_id')
            : $this->morphToMany(static::class, 'stakeholder', 'organizations_relatives', null, 'organization_id');

        return $relation->using(OrganizationRelative::class)
            ->withPivot('type', 'code', 'status', 'start_date', 'finish_date');
    }

    /**
     * {@inheritdoc}
     */
    public function companyRelatives(): MorphToMany
    {
        return $this->relatives(static::class)
            ->as('stakeholder');
    }

    /**
     * {@inheritdoc}
     */
    public function individualRelatives(): MorphToMany
    {
        return $this->relatives(Person::class)
            ->as('stakeholder');
    }

    /**
     * {@inheritdoc}
     */
    public function employees(): MorphToMany
    {
        return $this->relatives(Person::class)
            ->withPivot('is_primary', 'personnel_status')
            ->wherePivot('type', '=', StakeholderType::Employee)
            ->as('employment');
    }

    public function addEmployee(
        Person $employee,
        PersonnelStatus $status,
        CarbonInterface $startDate,
        bool $isPrimary = false
    ) {
        return $this->employees()->attach($employee, [
            'is_primary' => $isPrimary,
            'type' => StakeholderType::Employee,
            'personnel_status' => $status,
            'start_date' => $startDate,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function stakeholders(): HasMany
    {
        return $this->hasMany(OrganizationRelative::class);
    }

    /**
     * {@inheritdoc}
     */
    public function addStakeholder(StakeholderType $type, Entity $stakeholder): static
    {
        $this->relatives($stakeholder::class)->attach($stakeholder, [
            'type' => $type,
        ]);

        return $this->fresh();
    }
}

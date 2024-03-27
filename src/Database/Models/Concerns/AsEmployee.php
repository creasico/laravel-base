<?php

namespace Creasi\Base\Database\Models\Concerns;

use Creasi\Base\Database\Models\Business;
use Creasi\Base\Database\Models\BusinessRelative;
use Creasi\Base\Enums\StakeholderType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Company> $primaryEmployer
 *
 * @mixin \Creasi\Base\Database\Models\Contracts\Employee
 */
trait AsEmployee
{
    /**
     * Initialize the trait.
     */
    final protected function initializeAsEmployee(): void
    {
        $this->append('employer');

        $this->makeHidden('primaryEmployer');
    }

    /**
     * {@inheritdoc}
     */
    public function employer(): Attribute
    {
        $this->loadMissing('primaryEmployer');

        return Attribute::get(fn () => $this->primaryEmployer?->first());
    }

    /**
     * {@inheritdoc}
     */
    public function businessRelatives()
    {
        return $this->morphToMany(Business::class, 'stakeholder', 'business_relatives', 'business_id', 'stakeholder_id')
            ->using(BusinessRelative::class)
            ->withPivot('type', 'code', 'status', 'start_date', 'finish_date');
    }

    /**
     * {@inheritdoc}
     */
    public function companies(): MorphToMany
    {
        return $this->businessRelatives()
            ->wherePivot('type', '=', StakeholderType::Owner)
            ->as('ownership');
    }

    /**
     * {@inheritdoc}
     */
    public function employers(): MorphToMany
    {
        return $this->businessRelatives()
            ->wherePivot('type', '=', StakeholderType::Employee)
            ->withPivot('is_primary', 'employment_status')
            ->as('employment');
    }

    /**
     * {@inheritdoc}
     */
    public function primaryEmployer(): MorphToMany
    {
        return $this->employers()
            ->wherePivot('is_primary', '=', true)
            ->latest();
    }
}

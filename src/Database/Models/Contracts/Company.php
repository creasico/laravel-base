<?php

namespace Creasi\Base\Database\Models\Contracts;

use Creasi\Base\Database\Models\Entity;
use Creasi\Base\Enums\StakeholderType;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $employees
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $individualRelatives
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Company> $companyRelatives
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrganizationRelative> $stakeholders
 */
interface Company extends Stakeholder
{
    /**
     * @return MorphToMany|Personnel
     */
    public function employees(): MorphToMany;

    /**
     * @return MorphToMany|OrganizationRelative
     */
    public function companyRelatives(): MorphToMany;

    /**
     * @return MorphToMany|OrganizationRelative
     */
    public function individualRelatives(): MorphToMany;

    /**
     * @return HasMany|OrganizationRelative
     */
    public function stakeholders(): HasMany;

    public function addStakeholder(StakeholderType $type, Entity $stakeholder): static;
}

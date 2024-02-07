<?php

namespace Creasi\Base\Models\Contracts;

use Creasi\Base\Models\Entity;
use Creasi\Base\Models\Enums\BusinessRelativeType;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read \Creasi\Base\Models\Employment $employment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Employee> $employees
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $individualRelatives
 * @property-read \Illuminate\Database\Eloquent\Collection<int, static> $companyRelatives
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $stakeholders
 */
interface Company extends Stakeholder
{
    /**
     * @return BelongsToMany|Employee
     */
    public function employees(): BelongsToMany;

    /**
     * @return MorphToMany|BusinessRelative
     */
    public function companyRelatives(): MorphToMany;

    /**
     * @return MorphToMany|BusinessRelative
     */
    public function individualRelatives(): MorphToMany;

    /**
     * @return HasMany|BusinessRelative
     */
    public function stakeholders(): HasMany;

    public function addStakeholder(BusinessRelativeType $type, Entity $stakeholder): static;
}

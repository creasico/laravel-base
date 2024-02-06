<?php

namespace Creasi\Base\Models\Contracts;

use Creasi\Base\Models\Entity;
use Creasi\Base\Models\Enums\BusinessRelativeType;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Employee
     */
    public function employees();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|BusinessRelative
     */
    public function companyRelatives();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|BusinessRelative
     */
    public function individualRelatives();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|BusinessRelative
     */
    public function stakeholders();

    public function addStakeholder(
        BusinessRelativeType $type,
        Entity $stakeholder
    ): static;
}

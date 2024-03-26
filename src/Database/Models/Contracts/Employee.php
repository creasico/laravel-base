<?php

namespace Creasi\Base\Database\Models\Contracts;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property null|\Creasi\Base\Enums\Gender $gender
 * @property-read \Creasi\Base\Database\Models\Employment $employment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Company> $employers
 * @property-read null|Company $employer
 */
interface Employee extends Stakeholder
{
    /**
     * @return MorphToMany|Company
     */
    public function employers(): MorphToMany;

    /**
     * @return MorphToMany|Company
     */
    public function primaryEmployer(): MorphToMany;

    /**
     * Get the single primary company of this employee.
     */
    public function employer(): Attribute;
}

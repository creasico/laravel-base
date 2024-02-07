<?php

namespace Creasi\Base\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property null|\Creasi\Base\Models\Enums\Gender $gender
 * @property-read \Creasi\Base\Models\Employment $employment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Company> $employers
 * @property-read null|Company $company
 */
interface Employee extends Stakeholder
{
    /**
     * @return BelongsToMany|Company
     */
    public function employers(): BelongsToMany;

    /**
     * @return BelongsToMany|Company
     */
    public function primaryCompany(): BelongsToMany;
}

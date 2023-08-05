<?php

namespace Creasi\Base\Contracts;

/**
 * @property null|\Creasi\Base\Models\Enums\Gender $gender
 * @property-read \Creasi\Base\Models\Employment $employment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Company> $employers
 * @property-read null|Company $company
 */
interface Employee extends Stakeholder
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Company
     */
    public function employers();
}

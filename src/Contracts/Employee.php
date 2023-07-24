<?php

namespace Creasi\Base\Contracts;

/**
 * @property-read \Creasi\Base\Models\Employment $employment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Company> $employers
 * @property-read null|Company $company
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface Employee
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Company
     */
    public function employers();
}

<?php

namespace Creasi\Base\Contracts;

/**
 * @property-read \Creasi\Base\Models\Employment $employment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Employer> $employees
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface Employee
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Employer
     */
    public function employers();
}

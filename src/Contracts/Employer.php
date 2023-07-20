<?php

namespace Creasi\Base\Contracts;

/**
 * @property-read \Creasi\Base\Models\Employment $employment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Employee> $employees
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface Employer
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Employee
     */
    public function employees();
}

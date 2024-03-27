<?php

namespace Creasi\Base\Database\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read null|\Creasi\Base\Database\Models\Contracts\Employee $identity
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasIdentity
{
    /**
     * @return HasOne|\Creasi\Base\Database\Models\Contracts\Employee
     */
    public function identity(): HasOne;
}

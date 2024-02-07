<?php

namespace Creasi\Base\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read null|\Creasi\Base\Models\Contracts\Employee $identity
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasIdentity
{
    /**
     * @return HasOne|\Creasi\Base\Models\Contracts\Employee
     */
    public function identity(): HasOne;
}

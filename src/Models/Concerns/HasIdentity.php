<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Identity;

/**
 * @property-read ?Identity $identity
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasIdentity
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|Identity
     */
    public function identity()
    {
        return $this->morphOne(Identity::class, 'identity');
    }
}

<?php

namespace Creasi\Base\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property-read null|\Creasi\Base\Models\Profile $profile
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasProfile
{
    /**
     * @return MorphOne|HasIdentity
     */
    public function profile(): MorphOne;
}

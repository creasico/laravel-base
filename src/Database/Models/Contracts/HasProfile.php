<?php

namespace Creasi\Base\Database\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @property-read null|\Creasi\Base\Database\Models\Profile $profile
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

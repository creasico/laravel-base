<?php

namespace Creasi\Base\Models\Contracts;

/**
 * @property-read null|\Creasi\Base\Models\Profile $profile
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasProfile
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|HasIdentity
     */
    public function profile();
}

<?php

namespace Creasi\Base\Contracts;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasProfile
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|HasIdentity
     */
    public function profile();
}

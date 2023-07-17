<?php

namespace Creasi\Base\Contracts;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasIdentity
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|Identity
     */
    public function identity();
}

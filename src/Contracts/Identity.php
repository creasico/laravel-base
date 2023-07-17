<?php

namespace Creasi\Base\Contracts;

/**
 * @property-read null|\Illuminate\Database\Eloquent\Model|Entity $profile
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface Identity
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function profile();
}

<?php

namespace Creasi\Base\Contracts;

/**
 * @property-read null|\Creasi\Base\Models\Personnel $identity
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasIdentity
{
    public function identity();
}

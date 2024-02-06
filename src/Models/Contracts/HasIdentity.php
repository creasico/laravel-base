<?php

namespace Creasi\Base\Models\Contracts;

/**
 * @property-read null|\Creasi\Base\Models\Personnel $identity
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasIdentity
{
    public function identity();
}

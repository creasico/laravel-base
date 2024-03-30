<?php

namespace Creasi\Base\Database\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read null|\Creasi\Base\Database\Models\Contracts\Personnel $profile
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasProfile
{
    /**
     * @return HasOne|\Creasi\Base\Database\Models\Contracts\Personnel
     */
    public function profile(): HasOne;
}

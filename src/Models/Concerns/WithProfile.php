<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Profile;

/**
 * @property-read ?Profile $profile
 *
 * @mixin \Creasi\Base\Contracts\HasProfile
 */
trait WithProfile
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne|Profile
     */
    public function profile()
    {
        return $this->morphOne(Profile::class, 'identity');
    }
}

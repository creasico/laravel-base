<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Profile;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @mixin \Creasi\Base\Contracts\HasProfile
 */
trait WithProfile
{
    /**
     * {@inheritdoc}
     */
    public function profile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'identity');
    }
}

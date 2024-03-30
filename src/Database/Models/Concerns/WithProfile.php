<?php

namespace Creasi\Base\Database\Models\Concerns;

use Creasi\Base\Database\Models\Person;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin \Creasi\Base\Database\Models\Contracts\HasProfile
 */
trait WithProfile
{
    /**
     * {@inheritdoc}
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Person::class);
    }
}

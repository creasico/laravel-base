<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Personnel;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin \Creasi\Base\Contracts\HasIdentity
 */
trait WithIdentity
{
    /**
     * {@inheritdoc}
     */
    public function identity(): HasOne
    {
        return $this->hasOne(Personnel::class);
    }
}

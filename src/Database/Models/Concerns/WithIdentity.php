<?php

namespace Creasi\Base\Database\Models\Concerns;

use Creasi\Base\Database\Models\Personnel;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin \Creasi\Base\Database\Models\Contracts\HasIdentity
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

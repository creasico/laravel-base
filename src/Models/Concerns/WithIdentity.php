<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Personnel;

/**
 * @mixin \Creasi\Base\Contracts\HasIdentity
 */
trait WithIdentity
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|Personnel
     */
    public function identity()
    {
        return $this->hasOne(Personnel::class);
    }
}

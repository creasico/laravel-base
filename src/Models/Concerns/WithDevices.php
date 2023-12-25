<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\UserDevice;

/**
 * @mixin \Illuminate\Foundation\Auth\User
 */
trait WithDevices
{
    /**
     * {@inheritdoc}
     */
    public function devices()
    {
        return $this->hasMany(UserDevice::class);
    }
}

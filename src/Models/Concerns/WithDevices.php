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

    /**
     * {@inheritdoc}
     */
    public function deviceTokens(): array
    {
        static $tokens;

        if ($tokens) {
            return $tokens;
        }

        $this->loadMissing('devices');

        return $tokens = $this->devices->pluck('token')->toArray();
    }
}

<?php

namespace Creasi\Base\Database\Models\Concerns;

use Creasi\Base\Database\Models\UserDevice;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Illuminate\Foundation\Auth\User
 */
trait WithDevices
{
    /**
     * {@inheritdoc}
     */
    public function devices(): HasMany
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

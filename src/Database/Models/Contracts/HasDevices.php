<?php

namespace Creasi\Base\Database\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Creasi\Base\Database\Models\UserDevice> $devices
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasDevices
{
    /**
     * @return HasMany|\Creasi\Base\Database\Models\UserDevice
     */
    public function devices(): HasMany;

    /**
     * @return string[]
     */
    public function deviceTokens(): array;
}

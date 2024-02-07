<?php

namespace Creasi\Base\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Creasi\Base\Models\UserDevice> $devices
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasDevices
{
    /**
     * @return HasMany|\Creasi\Base\Models\UserDevice
     */
    public function devices(): HasMany;

    /**
     * @return string[]
     */
    public function deviceTokens(): array;
}

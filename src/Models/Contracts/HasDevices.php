<?php

namespace Creasi\Base\Models\Contracts;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Creasi\Base\Models\UserDevice> $devices
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
interface HasDevices
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices();

    /**
     * @return string[]
     */
    public function deviceTokens(): array;
}

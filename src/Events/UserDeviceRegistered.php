<?php

namespace Creasi\Base\Events;

use Creasi\Base\Models\UserDevice;

class UserDeviceRegistered
{
    public function __construct(
        public readonly UserDevice $device
    ) {
        // .
    }
}

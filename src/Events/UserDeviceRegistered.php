<?php

namespace Creasi\Base\Events;

use Creasi\Base\Database\Models\UserDevice;

class UserDeviceRegistered
{
    public function __construct(
        public readonly UserDevice $device
    ) {
        // .
    }
}

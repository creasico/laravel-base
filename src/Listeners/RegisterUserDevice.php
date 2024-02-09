<?php

namespace Creasi\Base\Listeners;

use Creasi\Base\Events\UserDeviceRegistered;
use Creasi\Base\Models\Contracts\HasDevices;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class RegisterUserDevice
{
    public function __construct(
        private Request $request
    ) {
        // .
    }

    public function handle(Login $event)
    {
        if (! $this->request->filled('device_token') || ! ($event->user instanceof HasDevices)) {
            return;
        }

        /** @var HasDevices */
        $user = $event->user;

        $device = $user->devices()->firstOrCreate([
            'token' => $this->request->input('device_token'),
        ]);

        \event(new UserDeviceRegistered($device));
    }
}

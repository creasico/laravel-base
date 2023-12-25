<?php

namespace Creasi\Base\Listeners;

use Creasi\Base\Events\UserDeviceRegistered;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Http\Request;

class RegisterUserDevice
{
    public function __construct(
        private Request $request
    ) {
        // .
    }

    public function handle(Authenticated $event)
    {
        if (! $this->request->filled('device')) {
            return;
        }

        /** @var \Creasi\Base\Contracts\HasDevices */
        $user = $event->user;

        $device = $user->devices()->firstOrCreate([
            'token' => $this->request->input('device'),
        ]);

        \event(new UserDeviceRegistered($device));
    }
}

<?php

use Creasi\Base\Models\Enums;

return [
    'routes_enable' => true,

    'routes_prefix' => 'base',

    'address' => [

        /*
        |--------------------------------------------------------------------------
        | Address Types
        |--------------------------------------------------------------------------
        |
        | This value defines which address types enum that will be used in the project.
        | By default there's only 2 types defined in the `AddressType` enum, which is
        | `Legal` and `Recident`.
        |
        */

        'types' => Enums\AddressType::class,
    ],
];

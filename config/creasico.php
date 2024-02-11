<?php

use Creasi\Base\Models\Enums;

return [
    'user_model' => env('CREASI_BASE_USER_MODEL', 'App\Models\User'),

    'routes_enable' => true,

    'routes_prefix' => 'base',

    /*
    |--------------------------------------------------------------------------
    | Credential keys
    |--------------------------------------------------------------------------
    |
    | This option controls the way user authenticate with the application, and
    | the values that will be used to retrieve the credential from the request.
    | So please make sure that the values are available in your `users` model.
    |
    */

    'credentials' => ['email'],

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

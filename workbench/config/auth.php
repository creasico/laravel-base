<?php

return [

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => Workbench\App\Models\User::class,
        ],
    ],

];

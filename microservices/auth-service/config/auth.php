<?php

return [
    'defaults' => [
        'guard'     => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver'   => 'token',
            'provider' => 'users',
            'hash'     => false,
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'mongodb',
            'model'  => App\Models\User::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];

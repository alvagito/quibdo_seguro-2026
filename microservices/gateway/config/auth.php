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
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => stdClass::class,
        ],
    ],
    'passwords' => [],
    'password_timeout' => 10800,
];

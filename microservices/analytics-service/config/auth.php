<?php
return [
    'defaults' => ['guard' => 'api', 'passwords' => 'users'],
    'guards' => [
        'api' => ['driver' => 'token', 'provider' => 'users'],
    ],
    'providers' => [
        'users' => ['driver' => 'mongodb', 'model' => App\Models\User::class],
    ],
    'passwords' => [],
    'password_timeout' => 10800,
];

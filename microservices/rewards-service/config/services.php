<?php

return [
    'auth_service' => [
        'url' => env('AUTH_SERVICE_URL', 'http://localhost:8001'),
    ],
    'notification_service' => [
        'url' => env('NOTIFICATION_SERVICE_URL', 'http://localhost:8004'),
    ],
];

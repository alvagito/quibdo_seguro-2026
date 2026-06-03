<?php

return [
    'auth_service' => [
        'url' => env('AUTH_SERVICE_URL', 'http://localhost:8001'),
    ],
    'incident_service' => [
        'url' => env('INCIDENT_SERVICE_URL', 'http://localhost:8002'),
    ],
    'rewards_service' => [
        'url' => env('REWARDS_SERVICE_URL', 'http://localhost:8003'),
    ],
    'notification_service' => [
        'url' => env('NOTIFICATION_SERVICE_URL', 'http://localhost:8004'),
    ],
    'analytics_service' => [
        'url' => env('ANALYTICS_SERVICE_URL', 'http://localhost:8005'),
    ],
];

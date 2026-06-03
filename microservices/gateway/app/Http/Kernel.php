<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        \Illuminate\Http\Middleware\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
    ];

    protected $middlewareGroups = [
        'api' => [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $middlewareAliases = [
        'throttle'   => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'rate.limit' => \App\Http\Middleware\RateLimitMiddleware::class,
    ];
}

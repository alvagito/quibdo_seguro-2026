<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;

use App\Models\PersonalAccessToken;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // El modelo User de MongoDB es compatible con EloquentUserProvider
        // Solo necesitamos registrar el driver 'mongodb' como alias de 'eloquent'
        Auth::provider('mongodb', function ($app, array $config) {
            return new EloquentUserProvider($app['hash'], $config['model']);
        });

        // Use a MongoDB-backed PersonalAccessToken model for Sanctum
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}

<?php
namespace App\Providers;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Facades\Auth;
class AuthServiceProvider extends ServiceProvider {
    public function boot(): void {
        Auth::provider('mongodb', function ($app, array $config) {
            return new EloquentUserProvider($app['hash'], $config['model']);
        });
    }
}

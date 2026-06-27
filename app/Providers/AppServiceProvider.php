<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Services\Contracts\AuthServiceInterface::class,
            function ($app) {
                $driver = config('services.grpc.driver', 'mock');

                if ($driver === 'grpc' && extension_loaded('grpc')) {
                    return new \App\Services\AuthGrpcService();
                }

                return new \App\Services\AuthMockService();
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

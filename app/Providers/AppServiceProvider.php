<?php

namespace App\Providers;

use App\Services\AuthGrpcService;
use App\Services\AuthMockService;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthServiceInterface::class,
            function ($app) {
                $driver = config('services.grpc.driver', 'mock');

                if ($driver === 'grpc' && extension_loaded('grpc')) {
                    return new AuthGrpcService;
                }

                return new AuthMockService;
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

<?php

namespace App\Providers;

use App\Interfaces\VerifyService;
use App\Services\FakeVerifyService;
use App\Services\GoogleVerifyService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (app()->environment('testing')) {
            $this->app->bind(VerifyService::class, function () {
                return new FakeVerifyService;
            });
        } else {
            $this->app->bind(VerifyService::class, function () {
                return new GoogleVerifyService;
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

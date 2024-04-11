<?php

namespace App\Providers;

use App\Contracts\Bus\CommandQueryBus;
use App\Infrastructure\Http\RequestMapper;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(RequestMapper::class);
        $this->app->bind(CommandQueryBus::class, \App\Infrastructure\Bus\CommandQueryBus::class);
    }
}

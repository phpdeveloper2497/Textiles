<?php

namespace App\Providers;

use App\Repositories\Contracts\BoxHistoryRepositoryInterface;
use App\Repositories\Contracts\BoxRepositoryInterface;
use App\Repositories\Eloquent\BoxHistoryRepository;
use App\Repositories\Eloquent\BoxRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            BoxRepositoryInterface::class,
            BoxRepository::class,
        );
        $this->app->bind(
            BoxHistoryRepositoryInterface::class,
            BoxHIstoryRepository::class,
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

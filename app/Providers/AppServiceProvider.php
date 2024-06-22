<?php

namespace App\Providers;

use App\Models\Box;
use App\Models\BoxHistory;
use App\Models\Handkerchief;
use App\Models\HandkerchiefHistory;
use App\Models\User;
use App\Policies\BoxHistoryPolicy;
use App\Policies\BoxPolicy;
use App\Policies\HandkerchiefHistoryPolicy;
use App\Policies\HandkerchiefPolicy;
use App\Policies\UserPolicy;
use App\Repositories\Contracts\BoxHistoryRepositoryInterface;
use App\Repositories\Contracts\BoxRepositoryInterface;
use App\Repositories\Eloquent\BoxHistoryRepository;
use App\Repositories\Eloquent\BoxRepository;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Box::class, BoxPolicy::class);
        Gate::policy(BoxHistory::class, BoxHistoryPolicy::class);
        Gate::policy(Handkerchief::class, HandkerchiefPolicy::class);
        Gate::policy(HandkerchiefHistory::class, HandkerchiefHistoryPolicy::class);
    }
}

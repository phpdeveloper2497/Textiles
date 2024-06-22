<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\HandkerchiefHistory;
use App\Policies\HandkerchiefHistoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
//        HandkerchiefHistory::class => HandkerchiefHistoryPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

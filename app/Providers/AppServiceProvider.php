<?php

namespace App\Providers;

use App\Models\Guest;
use App\Models\Wedding;
use App\Policies\GuestPolicy;
use App\Policies\WeddingPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
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
        Vite::prefetch(concurrency: 3);

        Gate::policy(Wedding::class, WeddingPolicy::class);
        Gate::policy(Guest::class, GuestPolicy::class);
    }
}

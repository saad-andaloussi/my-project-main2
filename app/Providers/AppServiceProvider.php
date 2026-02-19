<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Incident;
use App\Models\ResourceCategory;
use App\Policies\ReservationPolicy;
use App\Policies\ResourcePolicy;
use App\Policies\IncidentPolicy;
use App\Policies\ResourceCategoryPolicy;

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
        // Register policies
        Gate::policy(Reservation::class, ReservationPolicy::class);
        Gate::policy(Resource::class, ResourcePolicy::class);
        Gate::policy(Incident::class, IncidentPolicy::class);
        Gate::policy(ResourceCategory::class, ResourceCategoryPolicy::class);
    }
}

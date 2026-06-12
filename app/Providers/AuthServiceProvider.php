<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\Band::class => \App\Policies\BandPolicy::class,
        \App\Models\Release::class => \App\Policies\ReleasePolicy::class,
        \App\Models\Label::class => \App\Policies\LabelPolicy::class,
        \App\Models\Gig::class => \App\Policies\GigPolicy::class,
        \App\Models\Organizer::class => \App\Policies\OrganizerPolicy::class,
        \App\Models\Zine::class => \App\Policies\ZinePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('SUPERADMIN') ? true : null;
        });
    }
}

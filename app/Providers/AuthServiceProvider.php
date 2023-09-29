<?php

namespace App\Providers;

use App\Models\Coordinate;
use App\Models\Event;
use App\Models\Location;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\User;
use App\Policies\CoordinatePolicy;
use App\Policies\EventPolicy;
use App\Policies\LocationPolicy;
use App\Policies\ResourcePolicy;
use App\Policies\ResourceTypePolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Coordinate::class   => CoordinatePolicy::class,
        Event::class        => EventPolicy::class,
        Location::class     => LocationPolicy::class,
        Resource::class     => ResourcePolicy::class,
        ResourceType::class => ResourceTypePolicy::class,
        User::class         => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}

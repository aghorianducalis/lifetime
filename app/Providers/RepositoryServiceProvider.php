<?php

namespace App\Providers;

use App\Repositories\CoordinateRepository;
use App\Repositories\EventRepository;
use App\Repositories\Interfaces\CoordinateRepositoryInterface;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\LocationRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\ResourceTypeRepository;
use App\Repositories\UserRepository;
use App\Services\CoordinateService;
use App\Services\EventService;
use App\Services\LocationService;
use App\Services\ResourceService;
use App\Services\ResourceTypeService;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // bind repository interfaces to concrete repository class implementation
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CoordinateRepositoryInterface::class, CoordinateRepository::class);
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(ResourceTypeRepositoryInterface::class, ResourceTypeRepository::class);
        $this->app->bind(ResourceRepositoryInterface::class, ResourceRepository::class);

        // register a repositories as singleton
        $this->app->singleton(UserRepositoryInterface::class, function (Application $app) {
            return new UserRepository();
        });
        $this->app->singleton(CoordinateRepositoryInterface::class, function (Application $app) {
            return new CoordinateRepository();
        });
        $this->app->singleton(LocationRepositoryInterface::class, function (Application $app) {
            return new LocationRepository();
        });
        $this->app->singleton(EventRepository::class, function (Application $app) {
            return new EventRepository();
        });
        $this->app->singleton(ResourceRepositoryInterface::class, function (Application $app) {
            return new ResourceRepository();
        });
        $this->app->singleton(ResourceTypeRepositoryInterface::class, function (Application $app) {
            return new ResourceTypeRepository();
        });

        // register a services as singleton
        $this->app->singleton(UserService::class, function (Application $app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });
        $this->app->singleton(CoordinateService::class, function (Application $app) {
            return new CoordinateService($app->make(CoordinateRepositoryInterface::class));
        });
        $this->app->singleton(LocationService::class, function (Application $app) {
            return new LocationService($app->make(LocationRepositoryInterface::class));
        });
        $this->app->singleton(EventService::class, function (Application $app) {
            return new EventService($app->make(EventRepositoryInterface::class));
        });
        $this->app->singleton(ResourceService::class, function (Application $app) {
            return new ResourceService($app->make(ResourceRepositoryInterface::class));
        });
        $this->app->singleton(ResourceTypeService::class, function (Application $app) {
            return new ResourceTypeService($app->make(ResourceTypeRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

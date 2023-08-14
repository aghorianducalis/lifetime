<?php

namespace App\Providers;

use App\Repositories\EventRepository;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use App\Repositories\ResourceTypeRepository;
use App\Services\EventService;
use App\Services\ResourceTypeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ResourceTypeRepositoryInterface::class, ResourceTypeRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);

        $this->app->singleton(ResourceTypeRepository::class, function (Application $app) {
            return new ResourceTypeRepository();
        });

        $this->app->singleton(ResourceTypeService::class, function (Application $app) {
            return new ResourceTypeService($app->make(ResourceTypeRepository::class));
        });

        $this->app->singleton(EventRepository::class, function (Application $app) {
            return new EventRepository();
        });

        $this->app->singleton(EventService::class, function (Application $app) {
            return new EventService($app->make(EventRepository::class));
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

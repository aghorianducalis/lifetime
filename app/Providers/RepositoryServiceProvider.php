<?php

namespace App\Providers;

use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use App\Repositories\ResourceTypeRepository;
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

        $this->app->singleton(ResourceTypeRepository::class, function (Application $app) {
            return new ResourceTypeRepository();
        });

        $this->app->singleton(ResourceTypeService::class, function (Application $app) {
            return new ResourceTypeService($app->make(ResourceTypeRepository::class));
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

<?php

namespace App\Providers;

use App\Repositories\CoordinateRepository;
use App\Repositories\EventRepository;
use App\Repositories\Interfaces\CoordinateRepositoryInterface;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\LocationRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\ResourceRepository;
use App\Repositories\ResourceTypeRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Services\CoordinateService;
use App\Services\EventService;
use App\Services\LocationService;
use App\Services\ResourceService;
use App\Services\ResourceTypeService;
use App\Services\RolePermissionService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public array $bindings = [
        CoordinateRepositoryInterface::class   => CoordinateRepository::class,
        EventRepositoryInterface::class        => EventRepository::class,
        LocationRepositoryInterface::class     => LocationRepository::class,
        PermissionRepositoryInterface::class   => PermissionRepository::class,
        ResourceRepositoryInterface::class     => ResourceRepository::class,
        ResourceTypeRepositoryInterface::class => ResourceTypeRepository::class,
        RoleRepositoryInterface::class         => RoleRepository::class,
        UserRepositoryInterface::class         => UserRepository::class,
    ];

    /**
     * All of the container singletons that should be registered.
     *
     * @var array
     */
    public array $singletons = [
        // repositories
        CoordinateRepositoryInterface::class   => CoordinateRepository::class,
        EventRepositoryInterface::class        => EventRepository::class,
        LocationRepositoryInterface::class     => LocationRepository::class,
        PermissionRepositoryInterface::class   => PermissionRepository::class,
        ResourceRepositoryInterface::class     => ResourceRepository::class,
        ResourceTypeRepositoryInterface::class => ResourceTypeRepository::class,
        RoleRepositoryInterface::class         => RoleRepository::class,
        UserRepositoryInterface::class         => UserRepository::class,

        // services
        CoordinateService::class               => CoordinateService::class,
        EventService::class                    => EventService::class,
        LocationService::class                 => LocationService::class,
        ResourceService::class                 => ResourceService::class,
        ResourceTypeService::class             => ResourceTypeService::class,
        RolePermissionService::class           => RolePermissionService::class,
        UserService::class                     => UserService::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

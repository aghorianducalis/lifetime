<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function attachEvents(User $user, array $eventIds): void;

    public function detachEvents(User $user, array $eventIds): int;

    public function attachCoordinates(User $user, array $coordinateIds): void;

    public function detachCoordinates(User $user, array $coordinateIds): int;

    public function attachResources(User $user, array $resourceIds): void;

    public function detachResources(User $user, array $resourceIds): int;

    public function attachResourceTypes(User $user, array $resourceTypeIds): void;

    public function detachResourceTypes(User $user, array $resourceTypeIds): int;
}

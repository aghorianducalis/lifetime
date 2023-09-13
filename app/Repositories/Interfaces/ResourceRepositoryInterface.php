<?php

namespace App\Repositories\Interfaces;

use App\Models\Resource;

interface ResourceRepositoryInterface extends RepositoryInterface
{
    public function attachUsers(Resource $resource, array $userIds): void;

    public function detachUsers(Resource $resource, array $userIds): int;

    public function attachEvents(Resource $resource, array $eventIds): void;

    public function detachEvents(Resource $resource, array $eventIds): int;

}

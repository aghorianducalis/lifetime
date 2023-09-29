<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Resource;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class ResourceRepository extends EloquentRepository implements ResourceRepositoryInterface
{
    public function attachUsers(Resource $resource, array $userIds): void
    {
        $resource->users()->attach($userIds);
    }

    public function detachUsers(Resource $resource, array $userIds): int
    {
        return $resource->users()->detach($userIds);
    }

    public function attachEvents(Resource $resource, array $eventIds): void
    {
        $resource->events()->attach($eventIds);
    }

    public function detachEvents(Resource $resource, array $eventIds): int
    {
        return $resource->events()->detach($eventIds);
    }

    protected function query(): Builder
    {
        return Resource::query();
    }
}

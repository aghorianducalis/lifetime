<?php

namespace App\Repositories;

use App\Models\Resource;
use App\Repositories\Filters\Criteria;
use App\Repositories\Filters\HasUserFilter;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ResourceRepository extends EloquentRepository implements ResourceRepositoryInterface
{
    public function findByUser(?string $userId): Collection
    {
        $criteria = new Criteria;
        $criteria->push(new HasUserFilter($userId));

        return $this->matching($criteria);
    }

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

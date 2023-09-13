<?php

namespace App\Repositories;

use App\Models\Coordinate;
use App\Repositories\Filters\Criteria;
use App\Repositories\Filters\HasUserFilter;
use App\Repositories\Interfaces\CoordinateRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CoordinateRepository extends EloquentRepository implements CoordinateRepositoryInterface
{
    public function findByUser(?string $userId): Collection
    {
        $criteria = new Criteria;
        $criteria->push(new HasUserFilter($userId));

        return $this->matching($criteria);
    }

    public function attachUsers(Coordinate $coordinate, array $userIds): void
    {
        $coordinate->users()->attach($userIds);
    }

    public function detachUsers(Coordinate $coordinate, array $userIds): int
    {
        return $coordinate->users()->detach($userIds);
    }

    public function attachEvents(Coordinate $coordinate, array $eventIds): void
    {
        $coordinate->events()->attach($eventIds);
    }

    public function detachEvents(Coordinate $coordinate, array $eventIds): int
    {
        return $coordinate->events()->detach($eventIds);
    }

    protected function query(): Builder
    {
        return Coordinate::query();
    }
}

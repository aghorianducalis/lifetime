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

    protected function query(): Builder
    {
        return Coordinate::query();
    }
}

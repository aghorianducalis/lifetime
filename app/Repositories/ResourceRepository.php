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

    protected function query(): Builder
    {
        return Resource::query();
    }
}

<?php

namespace App\Repositories;

use App\Models\ResourceType;
use App\Repositories\Filters\Criteria;
use App\Repositories\Filters\TitleFilter;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class ResourceTypeRepository extends EloquentRepository implements ResourceTypeRepositoryInterface
{
    public function findByTitle(string $title)
    {
        $criteria = new Criteria;
        $criteria->push(new TitleFilter($title));

        return $this->matching($criteria);
    }

    protected function query(): Builder
    {
        return ResourceType::query();
    }
}

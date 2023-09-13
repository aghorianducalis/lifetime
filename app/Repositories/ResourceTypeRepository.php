<?php

namespace App\Repositories;

use App\Models\ResourceType;
use App\Repositories\Filters\Criteria;
use App\Repositories\Filters\HasUserFilter;
use App\Repositories\Filters\TitleFilter;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ResourceTypeRepository extends EloquentRepository implements ResourceTypeRepositoryInterface
{
    public function findByTitle(string $title): Collection
    {
        $criteria = new Criteria;
        $criteria->push(new TitleFilter($title));

        return $this->matching($criteria);
    }

    public function findByUser(?string $userId): Collection
    {
        $criteria = new Criteria;
        $criteria->push(new HasUserFilter($userId));

        return $this->matching($criteria);
    }

    public function attachUsers(ResourceType $resourceType, array $userIds): void
    {
        $resourceType->users()->attach($userIds);
    }

    public function detachUsers(ResourceType $resourceType, array $userIds): int
    {
        return $resourceType->users()->detach($userIds);
    }

    protected function query(): Builder
    {
        return ResourceType::query();
    }
}

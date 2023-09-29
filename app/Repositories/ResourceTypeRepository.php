<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ResourceType;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class ResourceTypeRepository extends EloquentRepository implements ResourceTypeRepositoryInterface
{
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

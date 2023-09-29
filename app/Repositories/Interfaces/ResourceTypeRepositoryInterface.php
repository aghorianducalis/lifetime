<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\ResourceType;

interface ResourceTypeRepositoryInterface extends RepositoryInterface
{
    public function attachUsers(ResourceType $resourceType, array $userIds): void;

    public function detachUsers(ResourceType $resourceType, array $userIds): int;
}

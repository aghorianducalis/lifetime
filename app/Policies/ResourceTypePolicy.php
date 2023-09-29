<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;
use App\Services\ResourceTypeService;

class ResourceTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::ViewResourceTypes->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, string $resourceTypeId): bool
    {
        return $user->can(PermissionEnum::ViewResourceType->value)
            && ResourceTypeService::getInstance()->doesResourceTypeBelongToUser($resourceTypeId, $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CreateResourceType->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string $resourceTypeId): bool
    {
        return $user->can(PermissionEnum::UpdateResourceType->value)
            && ResourceTypeService::getInstance()->doesResourceTypeBelongToUser($resourceTypeId, $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string $resourceTypeId): bool
    {
        return $user->can(PermissionEnum::DeleteResourceType->value)
            && ResourceTypeService::getInstance()->doesResourceTypeBelongToUser($resourceTypeId, $user->id);
    }
}

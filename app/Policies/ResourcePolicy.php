<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;
use App\Services\ResourceService;

class ResourcePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::ViewResources->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, string $resourceId): bool
    {
        return $user->can(PermissionEnum::ViewResource->value)
            && ResourceService::getInstance()->doesResourceBelongToUser($resourceId, $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CreateResource->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string $resourceId): bool
    {
        return $user->can(PermissionEnum::UpdateResource->value)
            && ResourceService::getInstance()->doesResourceBelongToUser($resourceId, $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string $resourceId): bool
    {
        return $user->can(PermissionEnum::DeleteResource->value)
            && ResourceService::getInstance()->doesResourceBelongToUser($resourceId, $user->id);
    }
}

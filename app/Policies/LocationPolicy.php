<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;
use App\Services\LocationService;

class LocationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::ViewLocations->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, string $locationId): bool
    {
        return $user->can(PermissionEnum::ViewLocation->value)
            && LocationService::getInstance()->doesLocationBelongToUser($locationId, $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CreateLocation->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string $locationId): bool
    {
        return $user->can(PermissionEnum::UpdateLocation->value)
            && LocationService::getInstance()->doesLocationBelongToUser($locationId, $user->id);

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string $locationId): bool
    {
        return $user->can(PermissionEnum::DeleteLocation->value)
            && LocationService::getInstance()->doesLocationBelongToUser($locationId, $user->id);
    }
}

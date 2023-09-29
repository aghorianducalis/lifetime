<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;
use App\Services\CoordinateService;

class CoordinatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::ViewCoordinates->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, int $coordinateId): bool
    {
        return $user->can(PermissionEnum::ViewCoordinate->value)
            && CoordinateService::getInstance()->doesCoordinateBelongToUser($coordinateId, $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CreateCoordinate->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, int $coordinateId): bool
    {
        return $user->can(PermissionEnum::UpdateCoordinate->value)
            && CoordinateService::getInstance()->doesCoordinateBelongToUser($coordinateId, $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, int $coordinateId): bool
    {
        return $user->can(PermissionEnum::DeleteCoordinate->value)
            && CoordinateService::getInstance()->doesCoordinateBelongToUser($coordinateId, $user->id);
    }
}

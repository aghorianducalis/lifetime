<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;
use App\Services\EventService;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::ViewEvents->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, string $eventId): bool
    {
        return $user->can(PermissionEnum::ViewEvent->value)
            && EventService::getInstance()->doesEventBelongToUser($eventId, $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CreateEvent->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string $eventId): bool
    {
        return $user->can(PermissionEnum::UpdateEvent->value)
            && EventService::getInstance()->doesEventBelongToUser($eventId, $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string $eventId): bool
    {
        return $user->can(PermissionEnum::DeleteEvent->value)
            && EventService::getInstance()->doesEventBelongToUser($eventId, $user->id);
    }
}

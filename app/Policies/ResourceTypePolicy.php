<?php

namespace App\Policies;

use App\Models\ResourceType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ResourceTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ResourceType $resource): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ResourceType $resource): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ResourceType $resource): bool
    {
        return true;
        return $resource->resourceItems->isEmpty();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ResourceType $resource): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ResourceType $resource): bool
    {
        return true;
    }
}

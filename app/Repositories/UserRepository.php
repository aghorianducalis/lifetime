<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        $model = new User();
        $model->fill($data);
        $model->email_verified_at = $data['email_verified_at'] ?? null;
        $model->save();

        return $model;
    }

    public function update(array $data, $id): User
    {
        /** @var User $model */
        $model = $this->find($id);
        $model->fill($data);
        $model->email_verified_at = $data['email_verified_at'] ?? $model->email_verified_at;
        $model->remember_token = $data['remember_token'] ?? $model->remember_token;
        $model->save();
        $model->refresh();

        return $model;
    }

    public function attachEvents(User $user, array $eventIds): void
    {
        $user->events()->attach($eventIds);
    }

    public function detachEvents(User $user, array $eventIds): int
    {
        return $user->events()->detach($eventIds);
    }

    public function attachCoordinates(User $user, array $coordinateIds): void
    {
        $user->coordinates()->attach($coordinateIds);
    }

    public function detachCoordinates(User $user, array $coordinateIds): int
    {
        return $user->coordinates()->detach($coordinateIds);
    }

    public function attachResources(User $user, array $resourceIds): void
    {
        $user->resources()->attach($resourceIds);
    }

    public function detachResources(User $user, array $resourceIds): int
    {
        return $user->resources()->detach($resourceIds);
    }

    public function attachResourceTypes(User $user, array $resourceTypeIds): void
    {
        $user->resourceTypes()->attach($resourceTypeIds);
    }

    public function detachResourceTypes(User $user, array $resourceTypeIds): int
    {
        return $user->resourceTypes()->detach($resourceTypeIds);
    }

    protected function query(): Builder
    {
        return User::query();
    }
}

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
        $model->email_verified_at = $data['email_verified_at'];
        $model->save();

        return $model;
    }

    public function update(array $data, $id): User
    {
        $model = $this->find($id);
        $model->fill($data);
        $model->email_verified_at = $data['email_verified_at'];
        $model->save();
        $model->refresh();

        return $model;
    }

    protected function query(): Builder
    {
        return User::query();
    }
}

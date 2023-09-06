<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    protected function query(): Builder
    {
        return User::query();
    }
}

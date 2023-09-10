<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserById(string $id): User
    {
        return $this->userRepository->find($id);
    }

    public function getAllUsers(): Collection
    {
        return $this->userRepository->matching();
    }

    public function createUser(array $data): User
    {
        $password = Hash::make($data['password']);
        $data['password'] = $password;

        return $this->userRepository->create($data);
    }

    public function updateUser(array $data, string $id): User
    {
        $password = Hash::make($data['password']);
        $data['password'] = $password;

        return $this->userRepository->update($data, $id);
    }

    public function deleteUser(string $id): bool
    {
        return $this->userRepository->delete($id);
    }
}

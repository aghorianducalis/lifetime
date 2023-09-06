<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserById(string $id)
    {
        return $this->userRepository->find($id);
    }

    public function getAllUsers(): Collection
    {
        return $this->userRepository->matching();
    }

    public function createUser(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function updateUser(array $data, string $id)
    {
        return $this->userRepository->update($data, $id);
    }

    public function deleteUser(string $id): bool
    {
        return $this->userRepository->delete($id);
    }
}

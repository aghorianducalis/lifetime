<?php

namespace App\Services;

use App\Models\ResourceType;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use Illuminate\Support\Collection;

class ResourceTypeService
{
    protected ResourceTypeRepositoryInterface $repository;

    public function __construct(ResourceTypeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getResourceTypeById(string $id): ResourceType
    {
        return $this->repository->find($id);
    }

    public function getResourceTypesByUser(?string $userId): Collection
    {
        return $this->repository->findByUser($userId);
    }

    public function getAllResourceTypes(): Collection
    {
        return $this->repository->matching();
    }

    public function createResourceType(array $data): ResourceType
    {
        return $this->repository->create($data);
    }

    public function updateResourceType(array $data, string $id): ResourceType
    {
        return $this->repository->update($data, $id);
    }

    public function deleteResourceType(string $id): bool
    {
        return $this->repository->delete($id);
    }
}

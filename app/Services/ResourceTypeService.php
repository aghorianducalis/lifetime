<?php

namespace App\Services;

use App\Models\ResourceType;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;
use Illuminate\Support\Collection;

class ResourceTypeService
{
    protected ResourceTypeRepositoryInterface $resourceTypeRepository;

    public function __construct(ResourceTypeRepositoryInterface $resourceTypeRepository)
    {
        $this->resourceTypeRepository = $resourceTypeRepository;
    }

    public function getResourceTypeById(string $id)
    {
        return $this->resourceTypeRepository->find($id);
    }

    public function getResourceTypesByUser(?string $userId): Collection
    {
        return $this->resourceTypeRepository->findByUser($userId);
    }

    public function getAllResourceTypes(): Collection
    {
        return $this->resourceTypeRepository->matching();
    }

    public function createResourceType(array $data): ResourceType
    {
        return $this->resourceTypeRepository->create($data);
    }

    public function updateResourceType(array $data, string $id)
    {
        return $this->resourceTypeRepository->update($data, $id);
    }

    public function deleteResourceType(string $id): bool
    {
        return $this->resourceTypeRepository->delete($id);
    }
}

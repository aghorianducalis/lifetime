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

    public function getResourceTypeById($id)
    {
        return $this->resourceTypeRepository->find($id);
    }

    public function getAllResourceTypes(): Collection
    {
        return $this->resourceTypeRepository->matching();
    }

    public function createResourceType(array $data): ResourceType
    {
        return $this->resourceTypeRepository->create($data);
    }

    public function updateResourceType(array $data, $id)
    {
        return $this->resourceTypeRepository->update($data, $id);
    }

    public function deleteResourceType($id): bool
    {
        return $this->resourceTypeRepository->delete($id);
    }
}

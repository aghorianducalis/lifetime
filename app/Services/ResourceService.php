<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Resource;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use Illuminate\Support\Collection;

class ResourceService
{
    protected ResourceRepositoryInterface $resourceRepository;

    public function __construct(ResourceRepositoryInterface $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    public function getAllResources(): Collection
    {
        return $this->resourceRepository->matching();
    }

    public function getResourceById(string $id): Resource
    {
        return $this->resourceRepository->find($id);
    }

    public function getResourcesByUser(?string $userId): Collection
    {
        return $this->resourceRepository->findByUser($userId);
    }

    public function doesResourceBelongToUser(string $resourceId, string $userId): bool
    {
        // todo rewrite this with matching()
        return $this->getResourcesByUser($userId)->pluck('id')->contains($resourceId);
    }

    public function createResource(array $data): Resource
    {
        return $this->resourceRepository->create($data);
    }

    public function updateResource(array $data, string $id): Resource
    {
        return $this->resourceRepository->update($data, $id);
    }

    public function deleteResource(string $id): bool
    {
        return $this->resourceRepository->delete($id);
    }

    public static function getInstance(): ResourceService
    {
        return app(ResourceService::class);
    }
}

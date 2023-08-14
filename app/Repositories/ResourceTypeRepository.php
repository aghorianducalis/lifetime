<?php

namespace App\Repositories;

use App\Models\ResourceType;
use App\Repositories\Interfaces\ResourceTypeRepositoryInterface;

class ResourceTypeRepository implements ResourceTypeRepositoryInterface
{
    public function all()
    {
        return ResourceType::all();
    }

    public function find($id)
    {
        return ResourceType::query()->findOrFail($id);
    }

    public function create(array $data)
    {
        return ResourceType::query()->create($data);
    }

    public function update(array $data, $id)
    {
        $resourceType = $this->find($id);
        $resourceType->update($data);

        return $resourceType;
    }

    public function delete($id): bool
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::query()->findOrFail($id);

        // todo check why this shit does not work in policy
        if ($resourceType->resources->isNotEmpty()) {
//            $resourceType->resources->delete();
        }

        $result = $resourceType->delete();

        return $result;
    }
}

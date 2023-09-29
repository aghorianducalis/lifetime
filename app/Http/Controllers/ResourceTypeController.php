<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexResourceTypeRequest;
use App\Http\Requests\StoreResourceTypeRequest;
use App\Http\Requests\UpdateResourceTypeRequest;
use App\Http\Resources\ResourceTypeCollection;
use App\Http\Resources\ResourceTypeResource;
use App\Models\ResourceType;
use App\Services\ResourceTypeService;
use Illuminate\Http\JsonResponse;

class ResourceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\IndexResourceTypeRequest $request
     * @param \App\Services\ResourceTypeService $service
     * @return \App\Http\Resources\ResourceTypeCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexResourceTypeRequest $request, ResourceTypeService $service): ResourceTypeCollection
    {
        $this->authorize('viewAny', ResourceType::class);

        $resourceTypes = $service->getResourceTypesByUser(auth()->id());

        return new ResourceTypeCollection($resourceTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreResourceTypeRequest $request
     * @param \App\Services\ResourceTypeService $service
     * @return \App\Http\Resources\ResourceTypeResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreResourceTypeRequest $request, ResourceTypeService $service): ResourceTypeResource
    {
        $this->authorize('create', ResourceType::class);

        $resourceType = $service->createResourceType($request->validated());

        return new ResourceTypeResource($resourceType);
    }

    /**
     * Display the specified resource.
     *
     * @param string $resourceTypeId
     * @param \App\Services\ResourceTypeService $service
     * @return \App\Http\Resources\ResourceTypeResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $resourceTypeId, ResourceTypeService $service): ResourceTypeResource
    {
        $this->authorize('view', [ResourceType::class, $resourceTypeId]);

        $resourceType = $service->getResourceTypeById($resourceTypeId);

        return new ResourceTypeResource($resourceType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateResourceTypeRequest $request
     * @param string $resourceTypeId
     * @param \App\Services\ResourceTypeService $service
     * @return \App\Http\Resources\ResourceTypeResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateResourceTypeRequest $request, string $resourceTypeId, ResourceTypeService $service): ResourceTypeResource
    {
        $this->authorize('update', [ResourceType::class, $resourceTypeId]);

        $resourceType = $service->updateResourceType($request->validated(), $resourceTypeId);

        return new ResourceTypeResource($resourceType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $resourceTypeId
     * @param \App\Services\ResourceTypeService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(string $resourceTypeId, ResourceTypeService $service): JsonResponse
    {
        $this->authorize('delete', [ResourceType::class, $resourceTypeId]);

        $result = $service->deleteResourceType($resourceTypeId);

        return response()->json(['result' => $result]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexResourceTypeRequest;
use App\Http\Requests\StoreResourceTypeRequest;
use App\Http\Requests\UpdateResourceTypeRequest;
use App\Http\Resources\ResourceTypeResource;
use App\Services\ResourceTypeService;

class ResourceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\ResourceTypeCollection
     */
    public function index(IndexResourceTypeRequest $request, ResourceTypeService $service)
    {
        $resourceTypes = $service->getResourceTypesByUser($request->user_id);

        return ResourceTypeResource::collection($resourceTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreResourceTypeRequest $request
     * @return \App\Http\Resources\ResourceTypeResource
     */
    public function store(StoreResourceTypeRequest $request, ResourceTypeService $service)
    {
        $resourceType = $service->createResourceType($request->validated());

        return new ResourceTypeResource($resourceType);
        return response()->json($resourceType, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $resourceTypeId
     * @return \App\Http\Resources\ResourceTypeResource
     */
    public function show(int $resourceTypeId, ResourceTypeService $service)
    {
        $resourceType = $service->getResourceTypeById($resourceTypeId);

        return new ResourceTypeResource($resourceType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateResourceTypeRequest $request
     * @param int $resourceTypeId
     * @return \App\Http\Resources\ResourceTypeResource
     */
    public function update(UpdateResourceTypeRequest $request, int $resourceTypeId, ResourceTypeService $service)
    {
        $resourceType = $service->updateResourceType($request->validated(), $resourceTypeId);

        return new ResourceTypeResource($resourceType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $resourceTypeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $resourceTypeId, ResourceTypeService $service)
    {
        $result = $service->deleteResourceType($resourceTypeId);

        return response()->json(['result' => $result]);
    }
}

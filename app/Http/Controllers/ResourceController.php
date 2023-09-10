<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexResourceRequest;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Http\Resources\ResourceResource;
use App\Services\ResourceService;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\IndexResourceRequest $request
     * @param \App\Services\ResourceService $service
     * @return \App\Http\Resources\LocationCollection
     */
    public function index(IndexResourceRequest $request, ResourceService $service)
    {
        $resources = $service->getResourcesByUser($request->user_id);

        return ResourceResource::collection($resources);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreResourceRequest $request
     * @param \App\Services\ResourceService $service
     * @return \App\Http\Resources\ResourceResource
     */
    public function store(StoreResourceRequest $request, ResourceService $service)
    {
        $resource = $service->createResource($request->validated());

        return new ResourceResource($resource);
    }

    /**
     * Display the specified resource.
     *
     * @param string $resourceId
     * @param \App\Services\ResourceService $service
     * @return \App\Http\Resources\ResourceResource
     */
    public function show(string $resourceId, ResourceService $service)
    {
        $resource = $service->getResourceById($resourceId);

        return new ResourceResource($resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateResourceRequest $request
     * @param string $resourceId
     * @param \App\Services\ResourceService $service
     * @return \App\Http\Resources\ResourceResource
     */
    public function update(UpdateResourceRequest $request, string $resourceId, ResourceService $service)
    {
        $resource = $service->updateResource($request->validated(), $resourceId);

        return new ResourceResource($resource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $resourceId
     * @param \App\Services\ResourceService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $resourceId, ResourceService $service)
    {
        $result = $service->deleteResource($resourceId);

        return response()->json(['result' => $result]);
    }
}

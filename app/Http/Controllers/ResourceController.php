<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexResourceRequest;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Http\Resources\ResourceCollection;
use App\Http\Resources\ResourceResource;
use App\Models\Resource;
use App\Services\ResourceService;
use Illuminate\Http\JsonResponse;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\IndexResourceRequest $request
     * @param \App\Services\ResourceService $service
     * @return \App\Http\Resources\ResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexResourceRequest $request, ResourceService $service): ResourceCollection
    {
        $this->authorize('viewAny', Resource::class);

        $resources = $service->getResourcesByUser(auth()->id());

        return new ResourceCollection($resources);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreResourceRequest $request
     * @param \App\Services\ResourceService $service
     * @return \App\Http\Resources\ResourceResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreResourceRequest $request, ResourceService $service): ResourceResource
    {
        $this->authorize('create', Resource::class);

        $resource = $service->createResource($request->validated());

        return new ResourceResource($resource);
    }

    /**
     * Display the specified resource.
     *
     * @param string $resourceId
     * @param \App\Services\ResourceService $service
     * @return \App\Http\Resources\ResourceResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $resourceId, ResourceService $service): ResourceResource
    {
        $this->authorize('view', [Resource::class, $resourceId]);

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateResourceRequest $request, string $resourceId, ResourceService $service): ResourceResource
    {
        $this->authorize('update', [Resource::class, $resourceId]);

        $resource = $service->updateResource($request->validated(), $resourceId);

        return new ResourceResource($resource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $resourceId
     * @param \App\Services\ResourceService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(string $resourceId, ResourceService $service): JsonResponse
    {
        $this->authorize('delete', [Resource::class, $resourceId]);

        $result = $service->deleteResource($resourceId);

        return response()->json(['result' => $result]);
    }
}

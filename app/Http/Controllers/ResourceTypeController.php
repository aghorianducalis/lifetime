<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceTypeRequest;
use App\Http\Requests\UpdateResourceTypeRequest;
use App\Models\ResourceType;

class ResourceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** @var \Illuminate\Database\Eloquent\Collection $resourceTypes */
        $resourceTypes = ResourceType::query()->get();

        return response()->json($resourceTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreResourceTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreResourceTypeRequest $request)
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::query()->create($request->validated());

        return response()->json($resourceType, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $resourceTypeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $resourceTypeId)
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::query()->findOrFail($resourceTypeId);

        return response()->json($resourceType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateResourceTypeRequest $request
     * @param int $resourceTypeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateResourceTypeRequest $request, int $resourceTypeId)
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::query()->findOrFail($resourceTypeId);

        $result = $resourceType->update($request->validated());

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $resourceTypeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $resourceTypeId)
    {
        /** @var ResourceType $resourceType */
        $resourceType = ResourceType::query()->findOrFail($resourceTypeId);

        $result = false;

        // todo check why this shit does not work in policy
        if ($resourceType->resources->isEmpty()) {
            $result = $resourceType->delete();
        }

        return response()->json($result);
    }
}

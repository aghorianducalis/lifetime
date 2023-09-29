<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexLocationRequest;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Http\Resources\LocationCollection;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\IndexLocationRequest $request
     * @param \App\Services\LocationService $service
     * @return \App\Http\Resources\LocationCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexLocationRequest $request, LocationService $service): LocationCollection
    {
        $this->authorize('viewAny', Location::class);

        $locations = $service->getLocationsByUser(auth()->id());

        return new LocationCollection($locations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreLocationRequest $request
     * @param \App\Services\LocationService $service
     * @return \App\Http\Resources\LocationResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreLocationRequest $request, LocationService $service): LocationResource
    {
        $this->authorize('create', Location::class);

        $location = $service->createLocation($request->validated());

        return new LocationResource($location);
    }

    /**
     * Display the specified resource.
     *
     * @param string $locationId
     * @param \App\Services\LocationService $service
     * @return \App\Http\Resources\LocationResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $locationId, LocationService $service): LocationResource
    {
        $this->authorize('view', [Location::class, $locationId]);

        $location = $service->getLocationById($locationId);

        return new LocationResource($location);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateLocationRequest $request
     * @param string $locationId
     * @param \App\Services\LocationService $service
     * @return \App\Http\Resources\LocationResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateLocationRequest $request, string $locationId, LocationService $service): LocationResource
    {
        $this->authorize('update', [Location::class, $locationId]);

        $location = $service->updateLocation($request->validated(), $locationId);

        return new LocationResource($location);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $locationId
     * @param \App\Services\LocationService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(string $locationId, LocationService $service): JsonResponse
    {
        $this->authorize('delete', [Location::class, $locationId]);

        $result = $service->deleteLocation($locationId);

        return response()->json(['result' => $result]);
    }
}

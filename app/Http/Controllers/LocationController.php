<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexLocationRequest;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Services\LocationService;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\IndexLocationRequest $request
     * @param \App\Services\LocationService $service
     * @return \App\Http\Resources\LocationCollection
     */
    public function index(IndexLocationRequest $request, LocationService $service)
    {
        $locations = $service->getAllLocations();

        return LocationResource::collection($locations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreLocationRequest $request
     * @param \App\Services\LocationService $service
     * @return \App\Http\Resources\LocationResource
     */
    public function store(StoreLocationRequest $request, LocationService $service)
    {
        $location = $service->createLocation($request->validated());

        return new LocationResource($location);
    }

    /**
     * Display the specified resource.
     *
     * @param string $locationId
     * @param \App\Services\LocationService $service
     * @return \App\Http\Resources\LocationResource
     */
    public function show(string $locationId, LocationService $service)
    {
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
     */
    public function update(UpdateLocationRequest $request, string $locationId, LocationService $service)
    {
        $location = $service->updateLocation($request->validated(), $locationId);

        return new LocationResource($location);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $locationId
     * @param \App\Services\LocationService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $locationId, LocationService $service)
    {
        $result = $service->deleteLocation($locationId);

        return response()->json(['result' => $result]);
    }
}

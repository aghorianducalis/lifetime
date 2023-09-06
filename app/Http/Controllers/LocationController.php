<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Models\Location;
use Illuminate\Http\Response;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** @var \Illuminate\Database\Eloquent\Collection $locations */
        $locations = Location::query()->get();

        return response()->json($locations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreLocationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLocationRequest $request)
    {
        /** @var Location $location */
        $location = Location::query()->create($request->validated());

        return response()->json($location, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param string $locationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $locationId)
    {
        /** @var Location $location */
        $location = Location::query()->findOrFail($locationId);

        return response()->json($location);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateLocationRequest $request
     * @param string $locationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateLocationRequest $request, string $locationId)
    {
        /** @var Location $location */
        $location = Location::query()->findOrFail($locationId);

        $result = $location->update($request->validated());

        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $locationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $locationId)
    {
        /** @var Location $location */
        $location = Location::query()->findOrFail($locationId);

        $result = false;

        // todo check why this shit does not work in policy
        if ($location->events->isEmpty()) {
            $result = $location->delete();
        }

        return response()->json($result);
    }
}

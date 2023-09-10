<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexCoordinateRequest;
use App\Http\Requests\StoreCoordinateRequest;
use App\Http\Requests\UpdateCoordinateRequest;
use App\Http\Resources\CoordinateResource;
use App\Services\CoordinateService;

class CoordinateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\IndexLocationRequest $request
     * @param \App\Services\CoordinateService $service
     * @return \App\Http\Resources\CoordinateCollection
     */
    public function index(IndexCoordinateRequest $request, CoordinateService $service)
    {
        $coordinates = $service->getCoordinatesByUser($request->user_id);

        return CoordinateResource::collection($coordinates);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCoordinateRequest $request
     * @param \App\Services\CoordinateService $service
     * @return \App\Http\Resources\CoordinateResource
     */
    public function store(StoreCoordinateRequest $request, CoordinateService $service)
    {
        $coordinate = $service->createCoordinate($request->validated());

        return new CoordinateResource($coordinate);
    }

    /**
     * Display the specified resource.
     *
     * @param int $coordinateId
     * @param \App\Services\CoordinateService $service
     * @return \App\Http\Resources\CoordinateResource
     */
    public function show(int $coordinateId, CoordinateService $service)
    {
        $coordinate = $service->getCoordinateById($coordinateId);

        return new CoordinateResource($coordinate);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCoordinateRequest $request
     * @param int $coordinateId
     * @param \App\Services\CoordinateService $service
     * @return \App\Http\Resources\CoordinateResource
     */
    public function update(UpdateCoordinateRequest $request, int $coordinateId, CoordinateService $service)
    {
        $coordinate = $service->updateCoordinate($request->validated(), $coordinateId);

        return new CoordinateResource($coordinate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $coordinateId
     * @param \App\Services\CoordinateService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $coordinateId, CoordinateService $service)
    {
        $result = $service->deleteCoordinate($coordinateId);

        return response()->json(['result' => $result]);
    }
}

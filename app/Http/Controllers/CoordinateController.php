<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexCoordinateRequest;
use App\Http\Requests\StoreCoordinateRequest;
use App\Http\Requests\UpdateCoordinateRequest;
use App\Http\Resources\CoordinateCollection;
use App\Http\Resources\CoordinateResource;
use App\Models\Coordinate;
use App\Services\CoordinateService;
use Illuminate\Http\JsonResponse;

class CoordinateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\IndexLocationRequest $request
     * @param \App\Services\CoordinateService $service
     * @return \App\Http\Resources\CoordinateCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexCoordinateRequest $request, CoordinateService $service): CoordinateCollection
    {
        $this->authorize('viewAny', Coordinate::class);

        $coordinates = $service->getCoordinatesByUser(auth()->id());

        return new CoordinateCollection($coordinates);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCoordinateRequest $request
     * @param \App\Services\CoordinateService $service
     * @return \App\Http\Resources\CoordinateResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreCoordinateRequest $request, CoordinateService $service): CoordinateResource
    {
        $this->authorize('create', Coordinate::class);

        $coordinate = $service->createCoordinate($request->validated());

        return new CoordinateResource($coordinate);
    }

    /**
     * Display the specified resource.
     *
     * @param int $coordinateId
     * @param \App\Services\CoordinateService $service
     * @return \App\Http\Resources\CoordinateResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(int $coordinateId, CoordinateService $service): CoordinateResource
    {
        $this->authorize('view', [Coordinate::class, $coordinateId]);

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateCoordinateRequest $request, int $coordinateId, CoordinateService $service): CoordinateResource
    {
        $this->authorize('update', [Coordinate::class, $coordinateId]);

        $coordinate = $service->updateCoordinate($request->validated(), $coordinateId);

        return new CoordinateResource($coordinate);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $coordinateId
     * @param \App\Services\CoordinateService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $coordinateId, CoordinateService $service): JsonResponse
    {
        $this->authorize('delete', [Coordinate::class, $coordinateId]);

        $result = $service->deleteCoordinate($coordinateId);

        return response()->json(['result' => $result]);
    }
}

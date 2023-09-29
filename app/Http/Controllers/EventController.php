<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexEventRequest;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventCollection;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\IndexEventRequest $request
     * @param \App\Services\EventService $service
     * @return \App\Http\Resources\EventCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(IndexEventRequest $request, EventService $service): EventCollection
    {
        $this->authorize('viewAny', Event::class);

        $events = $service->getEventsByUser(auth()->id());

        return new EventCollection($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreEventRequest $request
     * @param \App\Services\EventService $service
     * @return \App\Http\Resources\EventResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreEventRequest $request, EventService $service): EventResource
    {
        $this->authorize('create', Event::class);

        $event = $service->createEvent($request->validated());

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     *
     * @param string $eventId
     * @param \App\Services\EventService $service
     * @return \App\Http\Resources\EventResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(string $eventId, EventService $service): EventResource
    {
        $this->authorize('view', [Event::class, $eventId]);

        $event = $service->getEventById($eventId);

        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateEventRequest $request
     * @param string $eventId
     * @param \App\Services\EventService $service
     * @return \App\Http\Resources\EventResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateEventRequest $request, string $eventId, EventService $service): EventResource
    {
        $this->authorize('update', [Event::class, $eventId]);

        $event = $service->updateEvent($request->validated(), $eventId);

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $eventId
     * @param \App\Services\EventService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(string $eventId, EventService $service): JsonResponse
    {
        $this->authorize('delete', [Event::class, $eventId]);

        $result = $service->deleteEvent($eventId);

        return response()->json(['result' => $result]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\EventCollection
     */
    public function index(EventService $service)
    {
        $events = $service->getAllEvents();

        return EventResource::collection($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreEventRequest $request
     * @return \App\Http\Resources\EventResource
     */
    public function store(StoreEventRequest $request, EventService $service)
    {
        $event = $service->createEvent($request->validated());

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     *
     * @param string $eventId
     * @return \App\Http\Resources\EventResource
     */
    public function show(string $eventId, EventService $service)
    {
        $event = $service->getEventById($eventId);

        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateEventRequest $request
     * @param string $eventId
     * @return \App\Http\Resources\EventResource
     */
    public function update(UpdateEventRequest $request, string $eventId, EventService $service)
    {
        $event = $service->updateEvent($request->validated(), $eventId);

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $eventId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $eventId, EventService $service)
    {
        $result = $service->deleteEvent($eventId);

        return response()->json(['result' => $result]);
    }
}

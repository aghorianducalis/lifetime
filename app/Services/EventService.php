<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;

class EventService
{
    protected EventRepositoryInterface $repository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->repository = $eventRepository;
    }

    public function getEventById($id)
    {
        return $this->repository->find($id);
    }

    public function getAllEvents()
    {
        return $this->repository->all();
    }

    public function createEvent(array $data): Event
    {
        return $this->repository->create($data);
    }

    public function updateEvent(array $data, $id)
    {
        return $this->repository->update($data, $id);
    }

    public function deleteEvent($id)
    {
        return $this->repository->delete($id);
    }
}

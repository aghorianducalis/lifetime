<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Support\Collection;

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

    public function getAllEvents(): Collection
    {
        return $this->repository->matching();
    }

    public function createEvent(array $data): Event
    {
        return $this->repository->create($data);
    }

    public function updateEvent(array $data, $id)
    {
        return $this->repository->update($data, $id);
    }

    public function deleteEvent($id): bool
    {
        return $this->repository->delete($id);
    }
}

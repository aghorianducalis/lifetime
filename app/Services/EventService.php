<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Support\Collection;

class EventService
{
    protected EventRepositoryInterface $repository;

    public function __construct(EventRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getEventById(string $id): Event
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

    public function updateEvent(array $data, string $id): Event
    {
        return $this->repository->update($data, $id);
    }

    public function deleteEvent(string $id): bool
    {
        return $this->repository->delete($id);
    }
}

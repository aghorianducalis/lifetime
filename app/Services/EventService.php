<?php

declare(strict_types=1);

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

    public function getAllEvents(): Collection
    {
        return $this->repository->matching();
    }

    public function getEventById(string $eventId): Event
    {
        return $this->repository->find($eventId);
    }

    public function getEventsByUser(?string $userId): Collection
    {
        return $this->repository->findByUser($userId);
    }

    public function doesEventBelongToUser(string $eventId, string $userId): bool
    {
        // todo rewrite this with matching() or using query builder
        return $this->getEventById($eventId)?->users->pluck('id')->contains($userId);
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

    public static function getInstance(): EventService
    {
        return app(EventService::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class EventRepository extends EloquentRepository implements EventRepositoryInterface
{
    protected function query(): Builder
    {
        return Event::query();
    }

    public function attachCoordinates(Event $event, array $coordinateIds): void
    {
        $event->coordinates()->attach($coordinateIds);
    }

    public function detachCoordinates(Event $event, array $coordinateIds): int
    {
        return $event->coordinates()->detach($coordinateIds);
    }

    public function attachUsers(Event $event, array $userIds): void
    {
        $event->users()->attach($userIds);
    }

    public function detachUsers(Event $event, array $userIds): int
    {
        return $event->users()->detach($userIds);
    }

    public function attachResources(Event $event, array $resourceIds): void
    {
        $event->resources()->attach($resourceIds);
    }

    public function detachResources(Event $event, array $resourceIds): int
    {
        return $event->resources()->detach($resourceIds);
    }
}

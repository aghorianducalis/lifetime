<?php

namespace App\Repositories\Interfaces;

use App\Models\Event;

interface EventRepositoryInterface extends RepositoryInterface
{
    public function attachUsers(Event $event, array $userIds): void;

    public function detachUsers(Event $event, array $userIds): int;

    public function attachCoordinates(Event $event, array $coordinateIds): void;

    public function detachCoordinates(Event $event, array $coordinateIds): int;

    public function attachResources(Event $event, array $resourceIds): void;

    public function detachResources(Event $event, array $resourceIds): int;
}

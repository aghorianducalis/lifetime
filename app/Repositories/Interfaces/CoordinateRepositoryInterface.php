<?php

namespace App\Repositories\Interfaces;

use App\Models\Coordinate;

interface CoordinateRepositoryInterface extends RepositoryInterface
{
    public function attachUsers(Coordinate $coordinate, array $userIds): void;

    public function detachUsers(Coordinate $coordinate, array $userIds): int;

    public function attachEvents(Coordinate $coordinate, array $eventIds): void;

    public function detachEvents(Coordinate $coordinate, array $eventIds): int;
}

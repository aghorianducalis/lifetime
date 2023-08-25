<?php

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
}

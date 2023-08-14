<?php

namespace App\Repositories;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;

class EventRepository implements EventRepositoryInterface
{
    public function all()
    {
        return Event::all();
    }

    public function find($id)
    {
        return Event::query()->findOrFail($id);
    }

    public function create(array $data)
    {
        return Event::query()->create($data);
    }

    public function update(array $data, $id)
    {
        $event = $this->find($id);
        $event->update($data);

        return $event;
    }

    public function delete($id): bool
    {
        /** @var Event $event */
        $event = Event::query()->findOrFail($id);
        $result = $event->delete();

        return $result;
    }
}

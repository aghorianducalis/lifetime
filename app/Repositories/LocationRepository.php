<?php

namespace App\Repositories;

use App\Models\Location;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class LocationRepository extends EloquentRepository implements LocationRepositoryInterface
{
    protected function query(): Builder
    {
        return Location::query();
    }
}

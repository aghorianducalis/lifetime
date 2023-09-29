<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Location;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class LocationRepository extends EloquentRepository implements LocationRepositoryInterface
{
    public function findByUser(?string $userId): Collection
    {
        return $this->query()->whereHas('coordinate', function (Builder $query) use ($userId) {
            $query->whereHas('users', function (Builder $query) use ($userId) {
                $query->where('users.id', $userId);
            });
        })->get();
    }

    public function findByCoordinateIds(array $coordinateIds = []): Collection
    {
        return $this->query()->whereIn('coordinate_id', $coordinateIds)->get();
    }

    protected function query(): Builder
    {
        return Location::query();
    }
}

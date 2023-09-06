<?php

namespace App\Services;

use App\Models\Location;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use Illuminate\Support\Collection;

class LocationService
{
    protected LocationRepositoryInterface $locationRepository;

    public function __construct(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    public function getLocationById(string $id)
    {
        return $this->locationRepository->find($id);
    }

    public function getAllLocations(): Collection
    {
        return $this->locationRepository->matching();
    }

    public function createLocation(array $data): Location
    {
        return $this->locationRepository->create($data);
    }

    public function updateLocation(array $data, string $id)
    {
        return $this->locationRepository->update($data, $id);
    }

    public function deleteLocation(string $id): bool
    {
        return $this->locationRepository->delete($id);
    }
}

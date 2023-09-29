<?php

declare(strict_types=1);

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

    public function getAllLocations(): Collection
    {
        return $this->locationRepository->matching();
    }

    public function getLocationsByUser(?string $userId): Collection
    {
        return $this->locationRepository->findByUser($userId);
    }

    public function getLocationById(string $id): Location
    {
        return $this->locationRepository->find($id);
    }

    public function doesLocationBelongToUser(string $locationId, string $userId): bool
    {
        $location = $this->getLocationById($locationId);

        // todo rewrite using query builder or mathcing() with filters
        return CoordinateService::getInstance()->getCoordinatesByUser($userId)->pluck('id')->contains($location->coordinate_id);
    }

    public function createLocation(array $data): Location
    {
        return $this->locationRepository->create($data);
    }

    public function updateLocation(array $data, string $id): Location
    {
        return $this->locationRepository->update($data, $id);
    }

    public function deleteLocation(string $id): bool
    {
        return $this->locationRepository->delete($id);
    }

    public static function getInstance(): LocationService
    {
        return app(LocationService::class);
    }
}

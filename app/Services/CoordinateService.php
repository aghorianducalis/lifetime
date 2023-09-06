<?php

namespace App\Services;

use App\Models\Coordinate;
use App\Repositories\Interfaces\CoordinateRepositoryInterface;
use Illuminate\Support\Collection;

class CoordinateService
{
    protected CoordinateRepositoryInterface $coordinateRepository;

    public function __construct(CoordinateRepositoryInterface $coordinateRepository)
    {
        $this->coordinateRepository = $coordinateRepository;
    }

    public function getCoordinateById(int $id)
    {
        return $this->coordinateRepository->find($id);
    }

    public function getCoordinatesByUser(?string $userId): Collection
    {
        return $this->coordinateRepository->findByUser($userId);
    }

    public function getAllCoordinates(): Collection
    {
        return $this->coordinateRepository->matching();
    }

    public function createCoordinate(array $data): Coordinate
    {
        return $this->coordinateRepository->create($data);
    }

    public function updateCoordinate(array $data, int $id)
    {
        return $this->coordinateRepository->update($data, $id);
    }

    public function deleteCoordinate(int $id): bool
    {
        return $this->coordinateRepository->delete($id);
    }
}

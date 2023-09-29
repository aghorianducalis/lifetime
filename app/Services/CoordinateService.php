<?php

declare(strict_types=1);

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

    public function getAllCoordinates(): Collection
    {
        return $this->coordinateRepository->matching();
    }

    public function getCoordinateById(int $id): Coordinate
    {
        return $this->coordinateRepository->find($id);
    }

    public function getCoordinatesByUser(?string $userId): Collection
    {
        return $this->coordinateRepository->findByUser($userId);
    }

    public function doesCoordinateBelongToUser(int $coordinateId, string $userId): bool
    {
        // todo rewrite this with matching()
        return $this->getCoordinateById($coordinateId)?->users->pluck('id')->contains($userId);
    }

    public function createCoordinate(array $data): Coordinate
    {
        return $this->coordinateRepository->create($data);
    }

    public function updateCoordinate(array $data, int $id): Coordinate
    {
        return $this->coordinateRepository->update($data, $id);
    }

    public function deleteCoordinate(int $id): bool
    {
        return $this->coordinateRepository->delete($id);
    }

    public static function getInstance(): CoordinateService
    {
        return app(CoordinateService::class);
    }
}

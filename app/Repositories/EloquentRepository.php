<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Filters\Criteria;
use App\Repositories\Filters\HasUserFilter;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class EloquentRepository implements RepositoryInterface
{
    public function matching(Criteria $criteria = null): Collection
    {
        $query = $this->query();

        $criteria?->apply($query);

        return $query->get();
    }

    public function find($id): Model
    {
        return $this->query()->findOrFail($id);
    }

    public function findByUser(?string $userId): Collection
    {
        $criteria = new Criteria;
        $criteria->push(new HasUserFilter($userId));

        return $this->matching($criteria);
    }

    public function create(array $data): Model
    {
        return $this->query()->create($data);
    }

    public function update(array $data, $id): Model
    {
        $model = $this->find($id);
        $model->update($data);
        $model->refresh();

        return $model;
    }

    public function delete($id): bool
    {
        $model = $this->query()->findOrFail($id);
        $result = $model->delete();

        return $result;
    }

    abstract protected function query(): Builder;
}

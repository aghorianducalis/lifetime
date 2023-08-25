<?php

namespace App\Repositories;

use App\Repositories\Filters\Criteria;
use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class EloquentRepository implements RepositoryInterface
{
    public function matching(Criteria $criteria = null): Collection
    {
        $query = $this->query();

        $criteria?->apply($query);

        return $query->get();
    }

    public function find($id)
    {
        return $this->query()->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->query()->create($data);
    }

    public function update(array $data, $id)
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

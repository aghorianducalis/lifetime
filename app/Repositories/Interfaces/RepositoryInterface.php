<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Filters\Criteria;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    public function matching(Criteria $criteria = null): Collection;

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id): bool;

    public function find($id);
}

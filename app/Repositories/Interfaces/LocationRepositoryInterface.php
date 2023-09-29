<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface LocationRepositoryInterface extends RepositoryInterface
{
    public function findByUser(?string $userId): Collection;
}

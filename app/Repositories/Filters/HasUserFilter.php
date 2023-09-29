<?php

declare(strict_types=1);

namespace App\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;

class HasUserFilter implements FilterInterface
{
    protected ?string $userId;

    public function __construct(?string $userId)
    {
        $this->userId = $userId;
    }

    public function filter(Builder $query): Builder
    {
        if ($this->userId) {
            $query->whereHas('users', function (Builder $userQuery) {
                $userQuery->where('user_id', $this->userId);
            });
        }

        return $query;
    }
}

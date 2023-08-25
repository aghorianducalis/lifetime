<?php

namespace App\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;

class TitleFilter implements FilterInterface
{
    protected string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function filter(Builder $query): Builder
    {
        return $query->where('title', '=', $this->title);
    }
}

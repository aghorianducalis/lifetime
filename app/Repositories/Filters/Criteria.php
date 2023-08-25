<?php

namespace App\Repositories\Filters;

class Criteria
{
    /** @var FilterInterface[] $filters  */
    protected array $filters = [];

    public function push(FilterInterface $filter): void
    {
        $this->filters[] = $filter;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    public function apply($query): void
    {
        foreach ($this->filters as $filter) {
            $filter->filter($query);
        }
    }
}

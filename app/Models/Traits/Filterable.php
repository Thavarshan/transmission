<?php

namespace App\Models\Traits;

use App\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply all relevant space filters.
     *
     * @param \App\Filters\Filter $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filter(Filter $filters): Builder
    {
        return $filters->apply($this->query());
    }
}

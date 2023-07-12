<?php

use Illuminate\Support\Collection;

if (! function_exists('pluckColumn')) {
    /**
     * Pluck a column from a collection and return the unique values.
     *
     * @param \Illuminate\Support\Collection $collection
     * @param string                         $column
     *
     * @return array
     */
    function pluckColumn(Collection $collection, string $column): array
    {
        return $collection->pluck($column)
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }
}

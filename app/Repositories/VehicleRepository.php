<?php

namespace App\Repositories;

use App\Filters\VehicleFilter;
use Illuminate\Database\Eloquent\Builder;

class VehicleRepository extends Repository
{
    /**
     * Retrieve a list of vehicles queried by filters applied.
     *
     * @param \App\Filters\VehicleFilter $filter
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function list(VehicleFilter $filter): Builder
    {
        return $this->model->filter($filter);
    }

    /**
     * Retrieve a list of filterable fields.
     *
     * @return array
     */
    public function getFilterables(): array
    {
        $collection = $this->model->all();

        return [
            'makes' => pluckColumn($collection, 'make'),
            'models' => pluckColumn($collection, 'model'),
            'registrations' => pluckColumn($collection, 'registration'),
        ];
    }
}

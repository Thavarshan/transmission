<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class VehicleFilter extends Filter
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        'make',
        'model',
        'registration',
    ];

    /**
     * Filter the query by a given make.
     *
     * @param string $make
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function make(string $make): Builder
    {
        return $this->builder->where('make', $make);
    }

    /**
     * Filter the query by a given model.
     *
     * @param string $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(string $model): Builder
    {
        return $this->builder->where('model', $model);
    }

    /**
     * Filter the query by a given registration.
     *
     * @param string $registration
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function registration(string $registration): Builder
    {
        return $this->builder
            ->where('registration', 'like', "%{$registration}%");
    }
}

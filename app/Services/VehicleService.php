<?php

namespace App\Services;

use App\Filters\VehicleFilter;
use Illuminate\Support\Collection;
use App\Repositories\VehicleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VehicleService extends Service
{
    /**
     * Create a new vehicle service instance.
     *
     * @param \App\Repositories\VehicleRepository $repository
     *
     * @return void
     */
    public function __construct(VehicleRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Retrieve a list of vehicles queried by filters applied.
     *
     * @param \App\Filters\VehicleFilter $filter
     * @param bool                       $paginated
     *
     * @return \Illuminate\Support\Collection|Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function list(
        VehicleFilter $filter,
        bool $paginated = false
    ): Collection|LengthAwarePaginator {
        $data = $this->repository->list($filter)->latest();

        if ($paginated) {
            return $data->paginate()
                ->through(fn ($vehicle) => [
                    'id' => $vehicle->id,
                    'make' => $vehicle->make,
                    'model' => $vehicle->model,
                    'registration' => $vehicle->registration,
                    'created_at' => $vehicle->created_at,
                ]);
        }

        return $data->get();
    }

    /**
     * Retrieve a list of filterable fields.
     *
     * @return array
     */
    public function getFilterables(): array
    {
        return $this->repository->getFilterables();
    }
}

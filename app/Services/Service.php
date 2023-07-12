<?php

namespace App\Services;

use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Model;

abstract class Service
{
    /**
     * The repository instance.
     *
     * @var \App\Repositories\Repository
     */
    protected $repository;

    /**
     * Create a new service instance.
     *
     * @param \App\Repositories\Repository $repository
     *
     * @return void
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Find a specific resource by id.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function find(int $id): Model
    {
        return $this->repository->find($id);
    }

    /**
     * Find a specific resource by column and value.
     *
     * @param string $column
     * @param mixed  $value
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findBy(string $column, mixed $value): Model
    {
        return $this->repository->findBy($column, $value);
    }

    /**
     * Create a new resource.
     *
     * @param array<string, mixed> $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    /**
     * Update a specific resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array<string, mixed>                $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(Model $model, array $data): Model
    {
        return $this->repository->update($model, $data);
    }

    /**
     * Delete a specific resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(Model $model): void
    {
        $this->repository->delete($model);
    }
}

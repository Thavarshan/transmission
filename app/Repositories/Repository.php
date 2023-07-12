<?php

namespace App\Repositories;

use App\Models\Traits\Fillable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    use Fillable;

    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
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
        return $this->model->findOrFail($id);
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
        return $this->model->where($column, $value)->firstOrFail();
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
        return DB::transaction(function () use ($data) {
            return transform(
                $this->model->newInstance(),
                function (Model $model) use ($data) {
                    $model->fill(
                        $this->filterFillable($data, $model)
                    )->save();

                    return $model->fresh();
                }
            );
        });
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
        return DB::transaction(function () use ($model, $data) {
            $model->update(
                $this->filterFillable($data, $model)
            );

            return $model->fresh();
        });
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
        DB::transaction(function () use ($model) {
            return $model->delete();
        });
    }
}

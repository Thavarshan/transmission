<?php

namespace App\Models\Traits;

trait Fillable
{
    /**
     * Filter and etract only data allowable to be changed.
     *
     * @param array                                           $data
     * @param \Illuminate\Database\Eloquent\Model|string|null $resource
     *
     * @return array
     */
    public function filterFillable(array $data, $resource = null): array
    {
        if (is_null($resource)) {
            $resource = $this;
        }

        if (is_string($resource)) {
            $resource = new $resource();
        }

        return array_filter(
            $data,
            function (string $key) use ($resource): bool {
                return in_array($key, $resource->getFillable());
            },
            \ARRAY_FILTER_USE_KEY
        );
    }
}

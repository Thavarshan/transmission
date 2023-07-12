<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VehicleTestCase extends TestCase
{
    use RefreshDatabase;

    /**
     * Create and authenticate new user for testing purposes.
     *
     * @return void
     */
    public function withAuthenticatedUser(): void
    {
        $this->actingAs(User::factory()->create());
    }

    /**
     * Create a model factory builder for a vehicle class, name, and amount.
     *
     * @param array       $attributes
     * @param string|null $condition
     * @param int         $times
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    public function create(
        array $attributes = [],
        string $condition = null,
        int $times = null
    ) {
        $factory = Vehicle::factory();

        if (! is_null($condition)) {
            $factory = $factory->{$condition}();
        }

        return $factory->count($times)->create($attributes);
    }
}

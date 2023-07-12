<?php

namespace Tests\Unit;

use Mockery as m;
use App\Models\Vehicle;
use App\Filters\VehicleFilter;
use PHPUnit\Framework\TestCase;
use App\Repositories\VehicleRepository;
use Illuminate\Database\Eloquent\Builder;

class VehicleRepositoryTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testGetVehicleListing(): void
    {
        $filter = m::mock(VehicleFilter::class);
        $builder = m::mock(Builder::class);
        $model = m::mock(Vehicle::class);
        $model->shouldReceive('filter')
            ->once()
            ->andReturn($builder);

        $repository = new VehicleRepository($model);

        $this->assertInstanceOf(Builder::class, $repository->list($filter));
    }
}

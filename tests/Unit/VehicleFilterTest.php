<?php

namespace Tests\Unit;

use Mockery as m;
use Illuminate\Http\Request;
use App\Filters\VehicleFilter;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Builder;

class VehicleFilterTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testGetVehicleSpecificFilters(): void
    {
        $request = Request::create('/?model=R8&make=Audi&registration=123', 'GET');
        $vehicleFilter = new VehicleFilter($request);

        $this->assertEquals(
            [
                'make' => 'Audi',
                'model' => 'R8',
                'registration' => '123',
            ],
            $vehicleFilter->getFilterables()
        );
    }

    public function testGetVehicleSpecificFiltersWithEmptyRequest(): void
    {
        $request = Request::create('/', 'GET');
        $vehicleFilter = new VehicleFilter($request);

        $this->assertEquals([], $vehicleFilter->getFilterables());
    }

    public function testApplyFilters(): void
    {
        $request = Request::create('/?make=Camri', 'GET');
        $builder = m::mock(Builder::class);
        $builder->shouldReceive('where')
            ->once()
            ->with('make', 'Camri')
            ->andReturnSelf();

        $vehicleFilter = new VehicleFilter($request);
        $vehicleFilter->setUseCache(false);

        $builder = $vehicleFilter->apply($builder);

        $this->assertInstanceOf(Builder::class, $builder);
    }
}

<?php

namespace Tests\Unit;

use App\Filters\VehicleFilter;
use Filterable\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Mockery as m;
use PHPUnit\Framework\TestCase;

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
        Filter::disableCaching();

        $request = Request::create('/?make=Camri', 'GET');
        $builder = m::mock(Builder::class);
        $builder->shouldReceive('where')
            ->once()
            ->with('make', 'Camri')
            ->andReturnSelf();

        $vehicleFilter = new VehicleFilter($request);

        $builder = $vehicleFilter->apply($builder);

        $this->assertInstanceOf(Builder::class, $builder);
    }
}

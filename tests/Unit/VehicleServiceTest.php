<?php

namespace Tests\Unit;

use Mockery as m;
use App\Filters\VehicleFilter;
use PHPUnit\Framework\TestCase;
use App\Services\VehicleService;
use App\Repositories\VehicleRepository;
use Illuminate\Database\Eloquent\Builder;

class VehicleServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testGetVehicleListing(): void
    {
        $data = collect([
            [
                'id' => 1,
                'make' => 'Audi',
                'model' => 'R8',
                'registration' => '123',
                'created_at' => '2021-01-01 00:00:00',
            ],
        ]);
        $builder = m::mock(Builder::class);
        $builder->shouldReceive('latest')
            ->once()
            ->andReturnSelf();
        $builder->shouldReceive('get')
            ->once()
            ->andReturn($data);
        $filter = m::mock(VehicleFilter::class);
        $repository = m::mock(VehicleRepository::class);
        $repository->shouldReceive('list')
            ->once()
            ->andReturn($builder);

        $service = new VehicleService($repository);

        $this->assertEquals($data, $service->list($filter, false));
    }
}

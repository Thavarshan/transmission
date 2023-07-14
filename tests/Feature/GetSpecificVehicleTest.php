<?php

namespace Tests\Feature;

use Illuminate\Http\Response;

class GetSpecificVehicleTest extends VehicleTestCase
{
    /**
     * The vehicle instance.
     *
     * @var \App\Models\Vehicle
     */
    protected $vehicle;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vehicle = $this->create();
    }

    public function testGetVehicleInformationAsJson(): void
    {
        $this->withAuthenticatedUser();

        $response = $this->getJson('/vehicles/' . $this->vehicle->id);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'id',
            'registration',
            'make',
            'model',
            'created_at',
            'updated_at',
        ]);
    }
}

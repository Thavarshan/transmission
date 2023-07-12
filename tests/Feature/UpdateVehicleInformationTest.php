<?php

namespace Tests\Feature;

use Illuminate\Http\Response;

class UpdateVehicleInformationTest extends VehicleTestCase
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

    public function testUpdateVehicleInformation(): void
    {
        $this->withAuthenticatedUser();

        $this->assertDatabaseHas('vehicles', [
            'registration' => $this->vehicle->registration,
        ]);

        $response = $this->put('/vehicles/' . $this->vehicle->id, [
            'registration' => 'ABC123',
        ]);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirect('/vehicles');

        $this->assertDatabaseHas('vehicles', [
            'registration' => 'ABC123',
        ]);
    }

    public function testUpdateVehicleInformationThroughJson(): void
    {
        $this->withAuthenticatedUser();

        $this->assertDatabaseHas('vehicles', [
            'id' => $this->vehicle->id,
        ]);

        $response = $this->putJson('/vehicles/' . $this->vehicle->id, [
            'registration' => 'ABC123',
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('vehicles', [
            'registration' => 'ABC123',
        ]);
    }
}

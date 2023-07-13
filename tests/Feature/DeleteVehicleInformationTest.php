<?php

namespace Tests\Feature;

use Illuminate\Http\Response;

class DeleteVehicleInformationTest extends VehicleTestCase
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

    public function testDeleteVehicleInformation(): void
    {
        $this->withAuthenticatedUser();

        $this->assertDatabaseHas('vehicles', [
            'id' => $this->vehicle->id,
        ]);

        $response = $this->delete('/vehicles/' . $this->vehicle->id);

        $response->assertStatus(Response::HTTP_FOUND)->assertRedirect('/home');

        $this->assertDatabaseMissing('vehicles', [
            'id' => $this->vehicle->id,
        ]);
    }

    public function testDeleteVehicleInformationThroughJson(): void
    {
        $this->withAuthenticatedUser();

        $this->assertDatabaseHas('vehicles', [
            'id' => $this->vehicle->id,
        ]);

        $response = $this->deleteJson('/vehicles/' . $this->vehicle->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('vehicles', [
            'id' => $this->vehicle->id,
        ]);
    }
}

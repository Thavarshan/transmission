<?php

namespace Tests\Feature;

use Illuminate\Http\Response;

class SaveNewVehicleInformationTest extends VehicleTestCase
{
    public function testCreateNewVehicleEntry(): void
    {
        $this->withAuthenticatedUser();

        $response = $this->post('/vehicles', [
            'make' => 'Toyota',
            'model' => 'Camry',
            'registration' => 'ABC123',
        ]);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('vehicles', [
            'make' => 'Toyota',
            'model' => 'Camry',
            'registration' => 'ABC123',
        ]);
    }

    public function testCreateNewVehicleEntryThroughJson(): void
    {
        $this->withAuthenticatedUser();

        $response = $this->postJson('/vehicles', [
            'make' => 'Toyota',
            'model' => 'Camry',
            'registration' => 'ABC123',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('vehicles', [
            'make' => 'Toyota',
            'model' => 'Camry',
            'registration' => 'ABC123',
        ]);
    }
}

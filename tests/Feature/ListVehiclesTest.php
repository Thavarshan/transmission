<?php

namespace Tests\Feature;

use Illuminate\Http\Response;

class ListVehiclesTest extends VehicleTestCase
{
    /**
     * The vehicles collection instance.
     *
     * @var \App\Models\Vehicle
     */
    protected $vehicles;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vehicles = $this->create([], null, 5);

        // Create a vehicle with a specific registration to test filtering
        $this->create([
            'registration' => 'ABC123',
        ]);
    }

    public function testGetListOfVehicles(): void
    {
        $this->withAuthenticatedUser();

        $response = $this->get('/dashboard');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testGetListOfVehiclesAsJson(): void
    {
        $this->withAuthenticatedUser();

        $response = $this->getJson('/dashboard');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(6, $response->json('data')['data']);
        $response->assertJsonStructure(['data']);
    }

    public function testGetListOfFilteredVehiclesAsJson(): void
    {
        $this->withAuthenticatedUser();

        $response = $this->getJson('/dashboard?registration=ABC123');

        $response->assertStatus(Response::HTTP_OK);

        $this->assertCount(1, $response->json('data')['data']);
        $response->assertJsonStructure(['data']);
    }
}

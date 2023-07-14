<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = new Collection(json_decode(
            file_get_contents(resource_path('data/vehicles.json')),
            true
        ));

        return [
            'make' => $this->faker->randomElement($data->pluck('make')->toArray()),
            'model' => $this->faker->randomElement($data->pluck('model')->toArray()),
            'registration' => Str::upper(Str::random(10)),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VehicleType>
 */
class VehicleTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['Motor', 'Mobil', 'Bus', 'Truk']).' '.fake()->randomNumber(3),
            'hourly_rate' => fake()->randomElement([2000, 3000, 5000, 10000]),
        ];
    }
}

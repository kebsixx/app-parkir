<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vehicle_type_id' => VehicleType::factory(),
            'plate_number' => strtoupper(fake()->unique()->bothify('B #### ???')),
            'owner_name' => fake()->name(),
            'brand' => fake()->randomElement(['Honda', 'Yamaha', 'Toyota', 'Suzuki', 'Daihatsu']),
            'color' => fake()->randomElement(['Hitam', 'Putih', 'Merah', 'Biru', 'Silver']),
        ];
    }
}

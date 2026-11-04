<?php

namespace Database\Factories;

use App\Models\ParkingSession;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ParkingSession>
 */
class ParkingSessionFactory extends Factory
{
    public function definition(): array
    {
        $checkIn = now()->subMinutes(fake()->numberBetween(15, 300));

        return [
            'ticket_code' => 'PKR-'.now()->format('Ymd').'-'.strtoupper(Str::random(4)),
            'vehicle_id' => Vehicle::factory(),
            'check_in_by' => User::factory(),
            'check_out_by' => null,
            'hourly_rate' => fake()->randomElement([2000, 3000, 5000]),
            'check_in_at' => $checkIn,
            'check_out_at' => null,
            'duration_minutes' => null,
            'total_fee' => null,
            'status' => ParkingSession::STATUS_PARKED,
        ];
    }

    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            $checkOut = now()->subMinutes(fake()->numberBetween(1, 60));
            $minutes = max(1, (int) $attributes['check_in_at']->diffInMinutes($checkOut));
            $fee = (int) ceil($minutes / 60) * $attributes['hourly_rate'];

            return [
                'check_out_by' => User::factory(),
                'check_out_at' => $checkOut,
                'duration_minutes' => $minutes,
                'total_fee' => $fee,
                'status' => ParkingSession::STATUS_COMPLETED,
            ];
        });
    }
}

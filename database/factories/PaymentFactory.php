<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $amount = fake()->randomElement([2000, 4000, 6000, 10000]);

        return [
            'parking_session_id' => null,
            'received_by' => User::factory(),
            'method' => 'cash',
            'amount' => $amount,
            'paid_amount' => $amount,
            'change_amount' => 0,
            'paid_at' => now(),
        ];
    }
}

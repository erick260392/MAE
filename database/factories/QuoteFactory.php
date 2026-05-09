<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quote>
 */
class QuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'status' => $this->faker->randomElement(['pendiente', 'confirmada', 'cancelada']),
            'subtotal' => $this->faker->randomFloat(2, 100, 10000),
            'discount_type' => 'none',
            'discount_value' => 0,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total' => $this->faker->randomFloat(2, 100, 10000),
            'notes' => $this->faker->optional()->sentence(),
            'delivery_time' => $this->faker->optional()->words(2, true),
            'conditions' => 'Crédito 30 días',
            'seen_at' => null,
        ];
    }
}

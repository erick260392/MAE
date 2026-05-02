<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'type' => $this->faker->randomElement(['entrada', 'salida']),
            'quantity' => $this->faker->numberBetween(1, 100),
            'reason' => $this->faker->sentence(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'sku' => $this->faker->unique()->bothify('??-####'),
            'name' => ucfirst($name),
            'slug' => str($name)->slug(),
            'description' => $this->faker->paragraph(),
            'application' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'unit' => $this->faker->randomElement(['PZA', 'KG', 'L']),
            'image' => null,
            'active' => true,
        ];
    }
}

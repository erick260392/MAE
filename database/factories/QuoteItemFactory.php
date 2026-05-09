<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuoteItem>
 */
class QuoteItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 50);
        $unitPrice = $product->price;

        return [
            'quote_id' => Quote::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'original_unit_price' => $unitPrice,
            'unit_price' => $unitPrice,
            'discount_type' => 'none',
            'discount_value' => 0,
            'discount_amount' => 0,
            'subtotal' => $quantity * $unitPrice,
            'delivery_time' => $this->faker->optional()->words(2, true),
        ];
    }
}

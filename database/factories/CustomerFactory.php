<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'company' => $this->faker->optional()->company(),
            'rfc' => $this->faker->optional()->bothify('??########???'),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->optional()->email(),
            'address' => $this->faker->optional()->address(),
            'city' => $this->faker->optional()->city(),
            'zip_code' => $this->faker->optional()->postcode(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}

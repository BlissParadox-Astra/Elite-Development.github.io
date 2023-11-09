<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand_name' => $this->faker->word, // Generate a random word as the brand name
            'category_id' => $this->faker->numberBetween(1, 30), // Replace 1 and 10 with the appropriate range of category IDs
        ];
    }
}

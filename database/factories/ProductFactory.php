<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        return [
            "name" => $this->faker->word() . " " . $this->faker->word(),
            "price" => $this->faker->randomFloat(2, 0, 100),
            "image" => $this->faker->imageUrl(),
            "product_category_id" => ProductCategory::inRandomOrder()->first()->id,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
final class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'title' => $this->faker->sentence,
            'summary' => $this->faker->optional()->text(50),
            'price' => $this->faker->optional()->randomFloat(2, 1, 1000),
            'discount_price' => $this->faker->optional()->randomFloat(2, 1, 1000),
            'rating' => $this->faker->numberBetween(-2000, 99999),
            'views' => $this->faker->numberBetween(0, 999999),
            'category_id' => Category::factory(),
        ];
    }

    /**
     * Indicate that the product is published.
     */
    public function published(): self
    {
        return $this->state(fn (): array => ['published_at' => now()]);
    }
}

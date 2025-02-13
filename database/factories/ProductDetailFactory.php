<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProductDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductDetail>
 */
final class ProductDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->optional()->text(),
            'conditions' => $this->faker->optional()->text(),
            'instructions' => $this->faker->optional()->text(),
        ];
    }
}

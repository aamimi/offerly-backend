<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
final class CategoryFactory extends Factory
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
            'name' => $this->faker->name,
            'meta_title' => $this->faker->optional()->sentence,
            'meta_description' => $this->faker->optional()->sentence,
            'display_order' => $this->faker->randomNumber(),
            'views' => $this->faker->randomNumber(),
        ];
    }
}

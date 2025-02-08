<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\MetaTag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MetaTag>
 */
final class MetaTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->optional()->sentence,
            'description' => $this->faker->optional()->text,
            'keywords' => $this->faker->optional()->words(5, true),
            'og_title' => $this->faker->optional()->sentence,
            'og_description' => $this->faker->optional()->text,
            'og_image' => $this->faker->optional()->imageUrl(),
            'x_title' => $this->faker->optional()->sentence,
            'x_description' => $this->faker->optional()->text,
            'x_image' => $this->faker->optional()->imageUrl(),
            'robots_follow' => $this->faker->boolean,
            'robots_index' => $this->faker->boolean,
            'canonical_url' => $this->faker->optional()->url,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
final class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->word,
            'file_name' => $this->faker->word,
            'mime_type' => $this->faker->mimeType,
            'disk' => 'public',
            'size' => $this->faker->randomNumber(),
            'manipulations' => json_encode([]),
            'custom_properties' => json_encode([]),
            'generated_conversions' => json_encode([]),
            'responsive_images' => json_encode([]),
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;

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
            'order_column' => $this->faker->randomNumber(),
        ];
    }

    /**
     * Indicate that the media is for product collection name and disk.
     */
    public function forProduct(): self
    {
        return $this->state(fn (): array => [
            'collection_name' => Config::string('app.media_collections.products.name'),
            'disk' => Config::string('app.media_collections.products.disk'),
        ]);
    }
}

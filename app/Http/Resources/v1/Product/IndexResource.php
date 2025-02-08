<?php

declare(strict_types=1);

namespace App\Http\Resources\v1\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/** @mixin Product */
final class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'summary' => $this->summary,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'thumbnail' => $this->getThumbnail(),
            'rating' => $this->rating,
        ];
    }

    /**
     * Get a random thumbnail image.
     * temporary solution for the demo.
     * TODO: Implement a proper solution.
     */
    private function getThumbnail(): string
    {
        $images = [
            'https://picsum.photos/200/200?random=1',
            'https://picsum.photos/200/200?random=2',
            'https://picsum.photos/200/200?random=3',
            'https://picsum.photos/200/200?random=4',
            'https://picsum.photos/200/200?random=5',
            'https://picsum.photos/200/200?random=6',
            'https://picsum.photos/200/200?random=7',
            'https://picsum.photos/200/200?random=8',
            'https://picsum.photos/200/200?random=9',
            'https://picsum.photos/200/200?random=10',
        ];

        return $images[random_int(0, 9)];
    }
}

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
            'thumbnail' => 'https://placehold.co/150x150',
            'rating' => $this->rating,
        ];
    }
}

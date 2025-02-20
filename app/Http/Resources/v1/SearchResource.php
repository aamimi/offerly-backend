<?php

declare(strict_types=1);

namespace App\Http\Resources\v1;

use App\DTOs\SearchResponseDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/** @mixin SearchResponseDTO */
final class SearchResource extends JsonResource
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
            'categories' => Category\SearchResource::collection($this->categories),
            'products' => Product\SearchResource::collection($this->products),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Resources\v1\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
final class ShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'meta_title' => $this->getMetaTitle(),
            'meta_description' => $this->meta_description,
            'subcategories' => SubcategoryResource::collection($this->whenLoaded('subcategories')),
        ];
    }
}

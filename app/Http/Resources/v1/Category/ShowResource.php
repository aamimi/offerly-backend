<?php

declare(strict_types=1);

namespace App\Http\Resources\v1\Category;

use App\Http\Resources\v1\MetaTag\ShowResource as ShowMetaTagResource;
use App\Models\Category;
use App\Models\MetaTag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/** @mixin Category */
final class ShowResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'subcategories' => SubcategoryResource::collection($this->whenLoaded('subcategories')),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function with(Request $request): array
    {
        $metaTag = $this->metaTag ?? new MetaTag();

        $metaTag->title ??= $this->name;
        $metaTag->description ??= $this->name;
        $metaTag->og_title ??= $metaTag->title;
        $metaTag->x_title ??= $metaTag->title;
        $metaTag->og_description ??= $metaTag->description;
        $metaTag->x_description ??= $metaTag->description;

        return [
            'meta' => (new ShowMetaTagResource($metaTag))->toArray($request),
        ];
    }
}

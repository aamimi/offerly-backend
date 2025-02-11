<?php

declare(strict_types=1);

namespace App\Http\Resources\v1\Product;

use App\Http\Resources\v1\Media\ShowResource as ShowMediaResource;
use App\Http\Resources\v1\MetaTag\ShowResource as ShowMetaTagResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Override;

/** @mixin Product */
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
        $mediaCollection = $this->getMedia(Config::string('app.media_collections.products.name'));

        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'summary' => $this->summary,
            'description' => $this->description,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'images' => ShowMediaResource::collection($mediaCollection),
            'rating' => $this->rating,
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
        return [
            'meta' => (new ShowMetaTagResource($this->metaTag))->toArray($request),
        ];
    }
}

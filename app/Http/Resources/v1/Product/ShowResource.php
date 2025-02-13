<?php

declare(strict_types=1);

namespace App\Http\Resources\v1\Product;

use App\Http\Resources\v1\Media\ShowResource as ShowMediaResource;
use App\Http\Resources\v1\MetaTag\ShowResource as ShowMetaTagResource;
use App\Models\MetaTag;
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
        $productDetail = $this->details;

        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'summary' => $this->summary,
            'description' => $productDetail->description,
            'conditions' => $productDetail->conditions,
            'instructions' => $productDetail->instructions,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'images' => ShowMediaResource::collection($mediaCollection)->toArray($request),
            'rating' => $this->rating,
            'created_at' => $this->created_at->toDateTimeString(),
            'has_comments' => $this->comments_count > 0,
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

        $metaTag->title ??= $this->title;
        $metaTag->description ??= $this->summary;
        $metaTag->og_title ??= $metaTag->title;
        $metaTag->x_title ??= $metaTag->title;
        $metaTag->og_description ??= $metaTag->description;
        $metaTag->x_description ??= $metaTag->description;

        return [
            'meta' => (new ShowMetaTagResource($metaTag))->toArray($request),
        ];
    }
}

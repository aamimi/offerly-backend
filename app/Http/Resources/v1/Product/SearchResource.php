<?php

declare(strict_types=1);

namespace App\Http\Resources\v1\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Override;

/** @mixin Product */
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
        $thumbnail = $this->getFirstMedia(Config::string('app.media_collections.products.name'))?->getUrl();

        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'rating' => $this->rating,
            'thumbnail' => $thumbnail,
        ];
    }
}

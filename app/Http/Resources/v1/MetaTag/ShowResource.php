<?php

declare(strict_types=1);

namespace App\Http\Resources\v1\MetaTag;

use App\Models\MetaTag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/** @mixin MetaTag */
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
            'title' => $this->title,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_image' => $this->og_image,
            'x_title' => $this->x_title,
            'x_description' => $this->x_description,
            'x_image' => $this->x_image,
            'robots_follow' => $this->robots_follow,
            'robots_index' => $this->robots_index,
            'canonical_url' => $this->canonical_url,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Resources\v1\Media;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/** @mixin Media */
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
            'url' => $this->getFullUrl(),
            'name' => $this->name,
            'file_name' => $this->file_name,
            'size' => $this->size,
            'mime_type' => $this->mime_type,
        ];
    }
}

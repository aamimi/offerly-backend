<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\MetaTagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read int $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $og_title
 * @property string $og_description
 * @property string $og_image
 * @property string $x_title
 * @property string $x_description
 * @property string $x_image
 * @property bool $robots_follow
 * @property bool $robots_index
 * @property string $canonical_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Model $metaable
 */
final class MetaTag extends Model
{
    /** @use HasFactory<MetaTagFactory> */
    use HasFactory;

    /**
     * Get the model that the meta tag belongs to.
     *
     * @return MorphTo<Model, $this>
     */
    public function metaable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'robots_follow' => 'boolean',
            'robots_index' => 'boolean',
        ];
    }
}

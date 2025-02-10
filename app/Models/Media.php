<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MediaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;

final class Media extends SpatieMedia
{
    /** @use HasFactory<MediaFactory> */
    use HasFactory;
}

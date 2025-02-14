<?php

declare(strict_types=1);

namespace App\Filters\Comment;

final readonly class IndexFilter
{
    /**
     * IndexFilter constructor.
     */
    public function __construct(
        public string $slug,
    ) {}
}

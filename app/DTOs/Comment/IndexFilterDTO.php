<?php

declare(strict_types=1);

namespace App\DTOs\Comment;

final readonly class IndexFilterDTO
{
    /**
     * IndexFilter constructor.
     */
    public function __construct(
        public string $slug,
        public int $page = 1,
        public int $perPage = 5,
    ) {}
}

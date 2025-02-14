<?php

declare(strict_types=1);

namespace App\DTOs\Product;

final readonly class IndexFilterDTO
{
    /**
     * IndexFilter constructor.
     *
     * @param  array<int>|null  $categoriesIds
     */
    public function __construct(
        public int $skip = 0,
        public int $limit = 10,
        public ?string $search = null,
        public ?array $categoriesIds = null,
    ) {}
}

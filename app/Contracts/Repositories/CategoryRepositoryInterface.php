<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

interface CategoryRepositoryInterface
{
    /**
     * Get the category and its subcategories ids.
     *
     * @return array<int>|null
     */
    public function getCategoriesIdsBySlug(string $slug): ?array;
}

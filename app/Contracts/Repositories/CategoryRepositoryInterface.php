<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    /**
     * Get all parent categories with their subcategories.
     *
     * @return Collection<int, Category>
     */
    public function getParentCategories(): Collection;

    /**
     * Get a category by its slug.
     */
    public function getParentCategoryBySlug(string $slug): ?Category;

    /**
     * Get the category and its subcategories ids.
     *
     * @return array<int>|null
     */
    public function getCategoriesIdsBySlug(string $slug): ?array;

    /**
     * Search categories by name.
     *
     * @return Collection<int, Category>
     */
    public function getCategoriesByName(?string $searchTerm, int $limit = 5): Collection;
}

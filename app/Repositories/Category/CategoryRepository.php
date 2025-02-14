<?php

declare(strict_types=1);

namespace App\Repositories\Category;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;

final readonly class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get the category and its subcategories ids.
     *
     * @return array<int>|null
     */
    public function getCategoriesIdsBySlug(string $slug): ?array
    {
        $category = Category::query()
            ->select('id')
            ->where(column: 'slug', operator: '=', value: $slug)
            ->with('subcategories:id,parent_id')
            ->first();

        return $category instanceof Category // @phpstan-ignore-line
            ? [...$category->subcategories()->pluck('id')->toArray(), $category->id]
            : null;
    }
}

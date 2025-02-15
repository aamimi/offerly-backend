<?php

declare(strict_types=1);

namespace App\Repositories\Category;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use App\Queries\Category\CategoryQueryBuilder;
use App\Queries\Category\Filters\CategoryBySlugFilter;
use App\Queries\Category\Filters\ParentCategoriesFilter;
use App\Queries\Category\Relations\CategoryAllSubcategoriesRelation;
use App\Queries\Category\Relations\CategoryMetaTagRelation;
use App\Queries\Category\Relations\CategorySubcategoriesRelation;
use Illuminate\Database\Eloquent\Collection;

final readonly class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all parent categories with their subcategories.
     *
     * @return Collection<int, Category>
     */
    public function getParentCategories(): Collection
    {
        return new CategoryQueryBuilder()
            ->addFilter(new ParentCategoriesFilter())
            ->addFilter(new CategorySubcategoriesRelation())
            ->build()
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get a category by its slug.
     */
    public function getParentCategoryBySlug(string $slug): ?Category
    {
        return new CategoryQueryBuilder()
            ->addFilter(new CategoryBySlugFilter($slug))
            ->addFilter(new ParentCategoriesFilter())
            ->addFilter(new CategoryAllSubcategoriesRelation())
            ->addFilter(new CategoryMetaTagRelation())
            ->build()
            ->first();
    }

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

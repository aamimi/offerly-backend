<?php

declare(strict_types=1);

namespace App\Filters\Product;

use App\Models\Category;

final readonly class IndexFilter
{
    /**
     * IndexFilter constructor.
     */
    public function __construct(
        public int $skip = 0,
        public int $limit = 10,
        public ?string $categorySlug = null,
        public ?string $search = null
    ) {}

    /**
     * Get the category and load subcategories.
     */
    public function getCategory(): ?Category
    {
        return $this->categorySlug === null ? null : Category::query()
            ->select('id')
            ->where(column: 'slug', operator: '=', value: $this->categorySlug)
            ->with('subcategories:id,parent_id')
            ->first();
    }

    /**
     * Get the category and its subcategories ids.
     *
     * @return array<int>|null
     */
    public function getCategoriesIds(): ?array
    {
        $category = $this->getCategory();

        return $category instanceof Category // @phpstan-ignore-line
            ? [...$category->subcategories()->pluck('id')->toArray(), $category->id]
            : null;
    }
}

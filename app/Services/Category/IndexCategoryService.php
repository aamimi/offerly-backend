<?php

declare(strict_types=1);

namespace App\Services\Category;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

final readonly class IndexCategoryService
{
    /**
     * Create a new service instance.
     */
    public function __construct(private CategoryRepositoryInterface $repository) {}

    /**
     * Get all parent categories with their subcategories.
     *
     * @return Collection<int, Category>
     */
    public function handle(): Collection
    {
        return $this->repository->getParentCategories();
    }
}

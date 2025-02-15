<?php

declare(strict_types=1);

namespace App\Services\Category;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final readonly class ShowCategoryService
{
    /**
     * Create a new service instance.
     */
    public function __construct(private CategoryRepositoryInterface $repository) {}

    /**
     * Get a parent category by its slug.
     *
     * @throws ModelNotFoundException
     */
    public function handle(string $slug): Category
    {
        $category = $this->repository->getParentCategoryBySlug($slug);

        if (! $category instanceof Category) {
            throw new ModelNotFoundException();
        }

        return $category;
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Products;

use App\Filters\Product\IndexFilter;
use App\Http\Resources\v1\Product\IndexCollection;
use App\Queries\Product\IndexQuery;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Http\Request;

final readonly class IndexController
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private IndexQuery $query, private CategoryRepository $categoryRepository) {}

    /**
     * List of products.
     */
    public function __invoke(Request $request): IndexCollection
    {
        $categorySlug = $request->query('category');
        $search = $request->query('search');
        if (is_string($categorySlug)) {
            $categoriesIds = $this->categoryRepository->getCategoriesIdsBySlug($categorySlug);
        }

        $indexFilter = new IndexFilter(
            skip: (int) $request->query(key: 'skip', default: '0'),
            limit: (int) $request->query(key: 'limit', default: '10'),
            search: is_array($search) ? null : $search,
            categoriesIds: $categoriesIds ?? null,
        );
        $builder = $this->query->builder($indexFilter);

        return (new IndexCollection($builder->get()))
            ->additional([
                'total' => $builder->count(),
                'skip' => $indexFilter->skip,
                'limit' => $indexFilter->limit,
            ]);
    }
}

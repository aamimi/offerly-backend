<?php

declare(strict_types=1);

namespace App\Services\Product;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTOs\Product\IndexFilterDTO;
use App\DTOs\Product\ProductsResponseDTO;
use Illuminate\Http\Request;

final readonly class IndexProductService
{
    /**
     * Create a new service instance.
     */
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * Handle the incoming request.
     */
    public function handle(Request $request): ProductsResponseDTO
    {
        $categorySlug = $request->query('category');
        $search = $request->query('search');
        if (is_string($categorySlug)) {
            $categoriesIds = $this->categoryRepository->getCategoriesIdsBySlug($categorySlug);
        }

        $indexFilter = new IndexFilterDTO(
            skip: (int) $request->query(key: 'skip', default: '0'),
            limit: (int) $request->query(key: 'limit', default: '10'),
            search: is_array($search) ? null : $search,
            categoriesIds: $categoriesIds ?? null,
        );

        return $this->productRepository->getPublishedProducts($indexFilter);
    }
}

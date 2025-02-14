<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\DTOs\Product\IndexFilterDTO;
use App\DTOs\Product\ProductsResponseDTO;
use App\Models\Product;
use App\Queries\Product\Filters\CategoryProductsFilter;
use App\Queries\Product\Filters\PublishedProductsFilter;
use App\Queries\Product\Filters\SearchProductsFilter;
use App\Queries\Product\Filters\SlugProductsFilter;
use App\Queries\Product\ProductQueryBuilder;
use App\Queries\Product\Relations\ProductDetailsRelation;
use App\Queries\Product\Relations\ProductMediaRelation;

final readonly class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get published products.
     */
    public function getPublishedProducts(IndexFilterDTO $filter): ProductsResponseDTO
    {
        $queryBuilder = (new ProductQueryBuilder())
            ->addFilter(new PublishedProductsFilter())
            ->addFilter(new SearchProductsFilter($filter->search))
            ->addFilter(new CategoryProductsFilter($filter->categoriesIds))
            ->addFilter(new ProductMediaRelation(limit: 1))
            ->build();
        $products = $queryBuilder
            ->skip($filter->skip)
            ->take($filter->limit)
            ->get();

        return new ProductsResponseDTO(products: $products, total: $queryBuilder->count());
    }

    /**
     * Get published product by slug.
     */
    public function getPublishedProductBySlug(string $slug): ?Product
    {
        return (new ProductQueryBuilder())
            ->addFilter(new SlugProductsFilter($slug))
            ->addFilter(new PublishedProductsFilter())
            ->addFilter(new ProductDetailsRelation())
            ->addFilter(new ProductMediaRelation())
            ->build()
            ->with('metaTag')
            ->withCount('comments')
            ->first();
    }
}

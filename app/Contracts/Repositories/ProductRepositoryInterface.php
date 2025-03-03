<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\DTOs\Product\IndexFilterDTO;
use App\DTOs\Product\ProductsResponseDTO;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    /**
     * Get published products.
     */
    public function getPublishedProducts(IndexFilterDTO $filter): ProductsResponseDTO;

    /**
     * Get published product by slug.
     */
    public function getPublishedProductBySlug(string $slug): ?Product;

    /**
     * Get published products by title.
     *
     * @return Collection<int, Product>
     */
    public function getPublishedProductsByTitle(?string $searchTerm): Collection;
}

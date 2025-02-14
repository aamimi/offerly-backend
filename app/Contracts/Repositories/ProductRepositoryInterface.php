<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\DTOs\Product\IndexFilterDTO;
use App\DTOs\Product\ProductsResponseDTO;

interface ProductRepositoryInterface
{
    /**
     * Get products.
     */
    public function getProducts(IndexFilterDTO $filter): ProductsResponseDTO;
}

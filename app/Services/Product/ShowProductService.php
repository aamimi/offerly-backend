<?php

declare(strict_types=1);

namespace App\Services\Product;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;

final readonly class ShowProductService
{
    /**
     * ShowProductService constructor.
     */
    public function __construct(private ProductRepositoryInterface $productRepository) {}

    /**
     * Handle the incoming request.
     */
    public function handle(string $slug): ?Product
    {
        return $this->productRepository->getPublishedProductBySlug($slug);
    }
}

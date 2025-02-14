<?php

declare(strict_types=1);

namespace App\DTOs\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

final readonly class ProductsResponseDTO
{
    /**
     * Create a new DTO instance.
     *
     * @param  Collection<int, Product>  $products
     */
    public function __construct(
        public Collection $products,
        public int $total,
    ) {}
}

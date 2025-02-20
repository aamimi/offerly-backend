<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

final readonly class SearchResponseDTO
{
    /**
     * @param  Collection<int, Category>  $categories
     * @param  Collection<int, Product>  $products
     */
    public function __construct(
        public Collection $categories,
        public Collection $products,
    ) {}
}

<?php

declare(strict_types=1);

namespace App\Queries\Product\Filters;

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements QueryFilterInterface<Product>
 */
final readonly class CategoryProductsFilter implements QueryFilterInterface
{
    /**
     * CategoryProductsFilter constructor.
     *
     * @param  array<int>|null  $categoriesIds
     */
    public function __construct(private ?array $categoriesIds) {}

    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function apply(Builder $query): Builder
    {
        if ($this->categoriesIds === null || $this->categoriesIds === []) {
            return $query;
        }

        return $query->whereIn('category_id', $this->categoriesIds);
    }
}

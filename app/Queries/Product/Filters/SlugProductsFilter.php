<?php

declare(strict_types=1);

namespace App\Queries\Product\Filters;

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements QueryFilterInterface<Product>
 */
final readonly class SlugProductsFilter implements QueryFilterInterface
{
    /**
     * SlugProductsFilter constructor.
     */
    public function __construct(private string $slug) {}

    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function apply(Builder $query): Builder
    {
        return $query->where(column: 'slug', operator: '=', value: $this->slug);
    }
}

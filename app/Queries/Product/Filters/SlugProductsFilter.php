<?php

declare(strict_types=1);

namespace App\Queries\Product\Filters;

use App\Contracts\Queries\ProductQueryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final readonly class SlugProductsFilter implements ProductQueryInterface
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

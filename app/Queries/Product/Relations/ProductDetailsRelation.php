<?php

declare(strict_types=1);

namespace App\Queries\Product\Relations;

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements QueryFilterInterface<Product>
 */
final readonly class ProductDetailsRelation implements QueryFilterInterface
{
    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function apply(Builder $query): Builder
    {
        return $query->with(['details:id,product_id,description,conditions,instructions']);
    }
}

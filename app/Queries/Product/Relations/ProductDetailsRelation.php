<?php

declare(strict_types=1);

namespace App\Queries\Product\Relations;

use App\Contracts\Queries\ProductQueryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final readonly class ProductDetailsRelation implements ProductQueryInterface
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

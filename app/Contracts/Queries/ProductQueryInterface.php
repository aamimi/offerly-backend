<?php

declare(strict_types=1);

namespace App\Contracts\Queries;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

interface ProductQueryInterface
{
    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function apply(Builder $query): Builder;
}

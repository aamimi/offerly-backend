<?php

declare(strict_types=1);

namespace App\Contracts\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
interface QueryFilterInterface
{
    /**
     * Apply the filter to the query.
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public function apply(Builder $query): Builder;
}

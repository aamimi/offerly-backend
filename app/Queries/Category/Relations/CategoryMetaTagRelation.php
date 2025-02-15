<?php

declare(strict_types=1);

namespace App\Queries\Category\Relations;

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements QueryFilterInterface<Category>
 */
final class CategoryMetaTagRelation implements QueryFilterInterface
{
    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Category>  $query
     * @return Builder<Category>
     */
    public function apply(Builder $query): Builder
    {
        return $query->with('metaTag');
    }
}

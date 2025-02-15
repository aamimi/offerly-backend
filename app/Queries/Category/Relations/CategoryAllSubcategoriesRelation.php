<?php

declare(strict_types=1);

namespace App\Queries\Category\Relations;

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @implements QueryFilterInterface<Category>
 */
final class CategoryAllSubcategoriesRelation implements QueryFilterInterface
{
    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Category>  $query
     * @return Builder<Category>
     */
    public function apply(Builder $query): Builder
    {
        return $query->with(['subcategories' => function (Relation $query): void {
            $query->select(['id', 'name', 'slug', 'parent_id', 'image_url'])
                ->orderBy('name');
        }]);
    }
}

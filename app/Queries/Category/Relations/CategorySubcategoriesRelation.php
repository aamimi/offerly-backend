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
final class CategorySubcategoriesRelation implements QueryFilterInterface
{
    /**
     * The limit of subcategories to return.
     */
    public const int LIMIT = 5;

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
                ->orderBy('views', 'desc')
                ->orderBy('display_order')
                ->limit(self::LIMIT);
        }]);
    }
}

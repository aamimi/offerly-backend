<?php

declare(strict_types=1);

namespace App\Queries\Category\Filters;

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements QueryFilterInterface<Category>
 */
final readonly class CategoryBySlugFilter implements QueryFilterInterface
{
    /**
     * CategoryBySlugFilter constructor.
     */
    public function __construct(private string $slug) {}

    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Category>  $query
     * @return Builder<Category>
     */
    public function apply(Builder $query): Builder
    {
        return $query->where('slug', '=', $this->slug);
    }
}

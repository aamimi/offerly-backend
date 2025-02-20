<?php

declare(strict_types=1);

namespace App\Queries\Category\Filters;

use App\Contracts\Queries\QueryFilterInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

/**
 * @implements QueryFilterInterface<Category>
 */
final readonly class SearchCategoriesFilter implements QueryFilterInterface
{
    /**
     * CategoryBySlugFilter constructor.
     */
    public function __construct(private ?string $searchTerm) {}

    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Category>  $query
     * @return Builder<Category>
     */
    public function apply(Builder $query): Builder
    {
        if ($this->searchTerm === null || in_array(mb_trim($this->searchTerm), ['', '0'], true)) {
            return $query;
        }

        $searchTerm = mb_strtolower($this->searchTerm);

        return $query->whereRaw('LOWER(name) LIKE ?', ['%'.$searchTerm.'%']);
    }
}

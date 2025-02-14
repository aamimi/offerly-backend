<?php

declare(strict_types=1);

namespace App\Queries\Product\Filters;

use App\Contracts\Queries\ProductQueryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final readonly class SearchProductsFilter implements ProductQueryInterface
{
    /**
     * SearchProductsFilter constructor.
     */
    public function __construct(private ?string $searchTerm) {}

    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function apply(Builder $query): Builder
    {
        if ($this->searchTerm === null || in_array(mb_trim($this->searchTerm), ['', '0'], true)) {
            return $query;
        }

        $searchTerm = mb_strtolower($this->searchTerm);

        return $query->whereRaw('(LOWER(title) LIKE ? OR LOWER(summary) LIKE ?)', [
            '%'.$searchTerm.'%',
            '%'.$searchTerm.'%',
        ]);
    }
}

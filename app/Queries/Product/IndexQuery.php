<?php

declare(strict_types=1);

namespace App\Queries\Product;

use App\Filters\Product\IndexFilter;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Config;

final readonly class IndexQuery
{
    /**
     * Get the query builder.
     *
     * @return Builder<Product>
     */
    public function builder(IndexFilter $filter): Builder
    {
        return Product::query()
            ->select(['id', 'slug', 'title', 'summary', 'price', 'discount_price', 'rating'])
            ->whereNotNull('published_at')
            ->when(
                $filter->search !== null,
                function (Builder $query) use ($filter) {
                    $searchTerm = mb_strtolower((string) $filter->search);

                    return $query->whereRaw('(LOWER(title) LIKE ? OR LOWER(summary) LIKE ?)', [
                        '%'.$searchTerm.'%',
                        '%'.$searchTerm.'%',
                    ]);
                }
            )
            ->when(
                $filter->getCategoriesIds() !== null,
                fn (Builder $query) => $query->whereIn('category_id', $filter->getCategoriesIds())
            )
            ->with([
                'media' => fn (Relation $query) => $query->where(
                    column: 'collection_name',
                    operator: '=',
                    value: Config::string('app.media_collections.products.name')
                )
                    ->orderBy(column: 'order_column')
                    ->limit(value: 1),
            ])
            ->skip($filter->skip)
            ->take($filter->limit);
    }
}

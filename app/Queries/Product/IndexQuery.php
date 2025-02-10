<?php

declare(strict_types=1);

namespace App\Queries\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final readonly class IndexQuery
{
    /**
     * Get the query builder.
     *
     * @return Builder<Product>
     */
    public function builder(int $skip, int $limit): Builder
    {
        return Product::query()
            ->select(['id', 'slug', 'title', 'summary', 'price', 'discount_price', 'rating'])
            ->whereNotNull('published_at')
            ->skip($skip)
            ->limit($limit);
    }
}

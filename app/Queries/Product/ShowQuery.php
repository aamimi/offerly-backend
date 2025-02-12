<?php

declare(strict_types=1);

namespace App\Queries\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final readonly class ShowQuery
{
    /**
     * Get the query builder.
     *
     * @return Builder<Product>
     */
    public function builder(string $slug): Builder
    {
        return Product::query()
            ->select(
                ['id', 'slug', 'title', 'summary', 'description', 'price', 'discount_price', 'rating', 'created_at']
            )
            ->whereNotNull(columns: 'published_at')
            ->where(column: 'slug', operator: '=', value: $slug)
            ->with(['metaTag', 'media']);
    }
}

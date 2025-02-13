<?php

declare(strict_types=1);

namespace App\Queries\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Config;

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
                ['id', 'slug', 'title', 'summary', 'price', 'discount_price', 'rating', 'created_at']
            )
            ->whereNotNull(columns: 'published_at')
            ->where(column: 'slug', operator: '=', value: $slug)
            ->with([
                'details:id,product_id,description,conditions,instructions',
                'metaTag',
                'media' => fn (Relation $query) => $query->where(
                    column: 'collection_name',
                    operator: '=',
                    value: Config::string('app.media_collections.products.name')
                )
                    ->orderBy(column: 'order_column'),
            ]);
    }
}

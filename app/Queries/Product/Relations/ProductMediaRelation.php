<?php

declare(strict_types=1);

namespace App\Queries\Product\Relations;

use App\Contracts\Queries\ProductQueryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Config;

final readonly class ProductMediaRelation implements ProductQueryInterface
{
    /**
     * The maximum number of items to retrieve.
     */
    private int $limit;

    /**
     * ProductMediaRelation constructor.
     */
    public function __construct(?int $limit = null)
    {
        $this->limit = $limit ?? Config::integer('app.media_collections.products.maximum_number_of_items');
    }

    /**
     * Apply the query to the given Eloquent query builder.
     *
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function apply(Builder $query): Builder
    {
        return $query->with([
            'media' => fn (Relation $query) => $query->where(
                column: 'collection_name',
                operator: '=',
                value: Config::string('app.media_collections.products.name')
            )->orderBy(column: 'order_column')->limit(value: $this->limit),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Queries\Product;

use App\Models\Product;
use App\Queries\AbstractQueryBuilder;

/**
 * @extends AbstractQueryBuilder<Product>
 */
final class ProductQueryBuilder extends AbstractQueryBuilder
{
    /**
     * Create a new query builder instance.
     */
    public function __construct()
    {
        parent::__construct(
            modelClass: Product::class,
            columns: ['id', 'slug', 'title', 'summary', 'price', 'discount_price', 'rating', 'created_at']
        );
    }
}

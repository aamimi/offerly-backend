<?php

declare(strict_types=1);

namespace App\Queries\Product;

use App\Contracts\Queries\ProductQueryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final class ProductQueryBuilder
{
    /**
     * The filters to be applied to the query.
     *
     * @var array<ProductQueryInterface>
     */
    private array $filters = [];

    /**
     * The columns to be selected.
     *
     * @var array<int, string>
     */
    private array $columns = ['id', 'slug', 'title', 'summary', 'price', 'discount_price', 'rating', 'created_at'];

    /**
     * Add a filter to the query.
     */
    public function addFilter(ProductQueryInterface $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Set the columns to be selected.
     *
     * @param  array<int, string>  $columns
     */
    public function setSelectColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Build the query.
     *
     * @return Builder<Product>
     */
    public function build(): Builder
    {
        $query = Product::query()->select($this->columns);

        foreach ($this->filters as $queryFilter) {
            $query = $queryFilter->apply($query);
        }

        return $query;
    }
}

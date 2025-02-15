<?php

declare(strict_types=1);

namespace App\Queries;

use App\Contracts\Queries\QueryFilterInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
abstract class AbstractQueryBuilder
{
    /**
     * The filters to be applied to the query.
     *
     * @var array<QueryFilterInterface<TModel>>
     */
    protected array $filters = [];

    /**
     * @param  class-string<TModel>  $modelClass
     * @param  array<int, string>  $columns
     */
    public function __construct(
        /**
         * The model class name.
         */
        protected string $modelClass,
        /**
         * The columns to be selected.
         */
        protected array $columns
    ) {}

    /**
     * Add a filter to the query.
     *
     * @param  QueryFilterInterface<TModel>  $filter
     * @return $this
     */
    final public function addFilter(QueryFilterInterface $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Set the columns to be selected.
     *
     * @param  array<int, string>  $columns
     * @return $this
     */
    final public function setSelectColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Build the query.
     *
     * @return Builder<TModel>
     */
    final public function build(): Builder
    {
        /** @var Builder<TModel> $query */
        $query = $this->modelClass::query()->select($this->columns);

        foreach ($this->filters as $queryFilter) {
            $query = $queryFilter->apply($query);
        }

        return $query;
    }
}

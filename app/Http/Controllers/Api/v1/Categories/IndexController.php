<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Categories;

use App\Http\Resources\v1\Category\IndexCollection;
use App\Queries\Category\IndexQuery;

final readonly class IndexController
{
    /**
     * IndexController constructor
     */
    public function __construct(private IndexQuery $query) {}

    /**
     * Display a listing of the categories.
     */
    public function __invoke(): IndexCollection
    {
        return new IndexCollection($this->query->builder()->get());
    }
}

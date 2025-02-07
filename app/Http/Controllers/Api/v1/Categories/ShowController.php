<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Categories;

use App\Http\Resources\v1\Category\ShowResource;
use App\Queries\Category\ShowQuery;

final readonly class ShowController
{
    /**
     * ShowController constructor
     */
    public function __construct(private ShowQuery $query) {}

    /**
     * Display the specified category.
     */
    public function __invoke(string $slug): ShowResource
    {
        return new ShowResource($this->query->builder($slug)->firstOrFail());
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Products;

use App\Http\Resources\v1\Product\ShowResource;
use App\Queries\Product\ShowQuery;

final readonly class ShowController
{
    /**
     * ShowController constructor.
     */
    public function __construct(private ShowQuery $query) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $slug): ShowResource
    {
        return new ShowResource($this->query->builder($slug)->firstOrFail());
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Categories;

use App\Http\Resources\v1\Category\IndexCollection;
use App\Services\Category\IndexCategoryService;

final readonly class IndexController
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private IndexCategoryService $service) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(): IndexCollection
    {
        return new IndexCollection($this->service->handle());
    }
}

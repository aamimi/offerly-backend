<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Categories;

use App\Http\Resources\v1\Category\ShowResource;
use App\Services\Category\ShowCategoryService;

final readonly class ShowController
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private ShowCategoryService $service) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(string $slug): ShowResource
    {
        return new ShowResource($this->service->handle($slug));
    }
}

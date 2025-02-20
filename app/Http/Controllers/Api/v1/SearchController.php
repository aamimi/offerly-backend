<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\Resources\v1\SearchResource;
use App\Services\SearchService;
use Illuminate\Http\Request;

final readonly class SearchController
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private SearchService $searchService) {}

    /**
     * Handle the search request.
     */
    public function __invoke(Request $request): SearchResource
    {
        return new SearchResource($this->searchService->handle($request));
    }
}

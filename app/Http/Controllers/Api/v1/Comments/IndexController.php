<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Comments;

use App\DTOs\Comment\IndexFilterDTO;
use App\Http\Resources\v1\Comment\IndexCollection;
use App\Queries\Comment\IndexQuery;

final readonly class IndexController
{
    /**
     * Create a new controller instance.
     */
    public function __construct(private IndexQuery $query) {}

    /**
     * Display a listing of the product comments.
     */
    public function __invoke(string $slug): IndexCollection
    {
        $filter = new IndexFilterDTO($slug);

        return new IndexCollection($this->query->builder($filter)->simplePaginate(5));
    }
}
